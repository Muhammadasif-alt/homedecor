<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use App\Services\InquiryNotifier;
use App\Support\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function consultation(InquiryRequest $request, InquiryNotifier $notifier): RedirectResponse
    {
        return $this->store($request, 'consultation', $notifier);
    }

    public function contact(InquiryRequest $request, InquiryNotifier $notifier): RedirectResponse
    {
        return $this->store($request, 'contact', $notifier);
    }

    public function thanks(Seo $seo): View
    {
        abort_unless(session('inquiry_type'), 404);
        $seo->title('Thank You')->noindex();

        return view('pages.thanks', ['type' => session('inquiry_type')]);
    }

    private function store(InquiryRequest $request, string $type, InquiryNotifier $notifier): RedirectResponse
    {
        $data = collect($request->validated())->except(['consent', 'website', 'form_started', 'attachments'])->all();

        $inquiry = Inquiry::create($data + [
            'type' => $type,
            'status' => 'new',
            'attachments' => $this->storeAttachments($request),
            'consent_at' => now(),
            'source_url' => Str::limit((string) url()->previous(), 250, ''),
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 490, ''),
        ]);

        $notifier->notify($inquiry);

        return redirect()->route('inquiries.thanks')->with('inquiry_type', $type);
    }

    /** Attachments go to the private disk; admins download them through an authorised route. */
    private function storeAttachments(InquiryRequest $request): array
    {
        return collect($request->file('attachments', []))
            ->map(fn ($file) => [
                'path' => $file->store('inquiries/'.date('Y/m'), 'local'),
                'name' => Str::limit($file->getClientOriginalName(), 120, ''),
                'size' => $file->getSize(),
            ])->values()->all();
    }
}
