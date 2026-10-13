<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InquiryStatusRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InquiryController extends ResourceController
{
    public const TYPES = ['contact' => 'Contact message', 'consultation' => 'Consultation request'];

    protected static string $model = Inquiry::class;

    protected static string $parameter = 'inquiry';

    public static function middleware(): array
    {
        return [
            ...parent::middleware(),
            new Middleware('can:view,inquiry', only: ['attachment']),
        ];
    }

    public function index(Request $request): View
    {
        $inquiries = Inquiry::query()
            ->with('service:id,name')
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('message', 'like', "%{$term}%")))
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(30)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry): View
    {
        $inquiry->load(['service:id,name', 'notes.user:id,name']);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function update(InquiryStatusRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $inquiry->update($request->payload());

        return redirect()->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Status changed to “'.$inquiry->statusLabel().'”.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Lead deleted.');
    }

    /** Stream a customer attachment from the private disk (never publicly reachable). */
    public function attachment(Inquiry $inquiry, int $index): StreamedResponse
    {
        $file = ($inquiry->attachments ?? [])[$index] ?? null;
        $path = is_array($file) ? ($file['path'] ?? null) : $file;

        abort_unless(is_string($path) && str_starts_with($path, 'inquiries/') && ! str_contains($path, '..'), 404);
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, is_array($file) ? ($file['name'] ?? basename($path)) : basename($path));
    }
}
