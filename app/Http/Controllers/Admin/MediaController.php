<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Support\MediaPresenter;
use App\Http\Controllers\Admin\Support\MediaUsage;
use App\Http\Requests\Admin\MediaRequest;
use App\Http\Requests\Admin\MediaUploadRequest;
use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class MediaController extends ResourceController
{
    protected static string $model = Media::class;

    protected static string $parameter = 'media';

    public static function middleware(): array
    {
        return [
            ...parent::middleware(),
            new Middleware('can:viewAny,'.Media::class, only: ['library']),
            new Middleware('can:update,media', only: ['replace']),
        ];
    }

    public function __construct(private readonly ImageOptimizer $optimizer) {}

    public function index(Request $request): View
    {
        $media = $this->search($request)->paginate(48)->withQueryString();

        return view('admin.media.index', ['media' => $media]);
    }

    /** JSON feed for the media picker: {data: [{id, url, alt, title, category}], next_page_url, …}. */
    public function library(Request $request): JsonResponse
    {
        $page = $this->search($request)->paginate(40)
            ->through(fn (Media $m) => MediaPresenter::item($m));

        return response()->json($page);
    }

    /** Multi-file upload. Responds with JSON for the picker, or redirects for the library page. */
    public function store(MediaUploadRequest $request): JsonResponse|RedirectResponse
    {
        $created = [];
        $failed = [];

        foreach ($request->file('files') as $file) {
            try {
                $created[] = $this->optimize($file, [
                    'category' => $request->validated('category'),
                    'alt' => $request->validated('alt'),
                ]);
            } catch (RuntimeException) {
                $failed[] = $file->getClientOriginalName();
            }
        }

        $message = count($created).' image(s) uploaded.'
            .($failed ? ' Could not read: '.implode(', ', $failed).'.' : '');

        if ($request->expectsJson()) {
            return response()->json([
                'data' => array_map(fn (Media $m) => MediaPresenter::item($m), $created),
                'message' => $message,
                'errors' => $failed ? ['files' => ["Could not read: ".implode(', ', $failed)]] : [],
            ], $created ? 201 : 422);
        }

        return redirect()->route('admin.media.index')->with($created ? 'success' : 'error', $message);
    }

    public function edit(Media $media, MediaUsage $usage): View
    {
        return view('admin.media.edit', ['media' => $media, 'usage' => $usage->for($media)]);
    }

    public function update(MediaRequest $request, Media $media): RedirectResponse
    {
        $media->update($request->payload());

        return $this->done('admin.media.edit', $media, 'Image details saved.');
    }

    /** Swap the file but keep the record (and every reference to it). */
    public function replace(Request $request, Media $media): RedirectResponse
    {
        $request->validate(['file' => MediaUploadRequest::FILE_RULES], [
            'file.max' => 'The image must be 10 MB or smaller.',
            'file.mimetypes' => 'Only JPG, JFIF, PNG and WebP images are allowed.',
        ]);

        try {
            $fresh = $this->optimize($request->file('file'), ['title' => $media->title, 'category' => $media->category]);
        } catch (RuntimeException $e) {
            return back()->withErrors(['file' => 'That file could not be read as an image.']);
        }

        DB::transaction(function () use ($media, $fresh) {
            $this->optimizer->delete($media);
            $media->update($fresh->only(['path', 'original_name', 'width', 'height', 'size', 'variants']));
            $fresh->delete(); // temporary record only; its files now belong to $media
        });

        return $this->done('admin.media.edit', $media, 'Image replaced. All pages using it now show the new file.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        DB::transaction(function () use ($media) {
            $media->delete();
            $this->optimizer->delete($media);
        });

        return $this->done('admin.media.index', [], 'Image deleted.');
    }

    private function optimize(UploadedFile $file, array $attributes): Media
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        return $this->optimizer->store($file->getRealPath(), 'media/uploads/'.date('Y/m'), array_filter([
            'title' => $attributes['title'] ?? Str::headline($name),
            'alt' => $attributes['alt'] ?? null,
            'category' => $attributes['category'] ?? null,
            'original_name' => $file->getClientOriginalName(),
        ]));
    }

    private function search(Request $request)
    {
        return Media::query()
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('alt', 'like', "%{$term}%")
                ->orWhere('original_name', 'like', "%{$term}%")))
            ->when($request->query('category'), fn ($q, $category) => $q->where('category', $category))
            ->latest('id');
    }
}
