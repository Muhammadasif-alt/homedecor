<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InquiryNoteRequest;
use App\Models\Inquiry;
use App\Models\InquiryNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Internal notes on a lead (never shown to the customer).
 */
class InquiryNoteController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('can:update,inquiry')];
    }

    public function store(InquiryNoteRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $inquiry->notes()->create($request->payload() + ['user_id' => $request->user()->id]);

        return redirect()->route('admin.inquiries.show', $inquiry)->with('success', 'Note added.');
    }

    public function destroy(Inquiry $inquiry, InquiryNote $note): RedirectResponse
    {
        abort_unless($note->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        $note->delete();

        return redirect()->route('admin.inquiries.show', $inquiry)->with('success', 'Note deleted.');
    }
}
