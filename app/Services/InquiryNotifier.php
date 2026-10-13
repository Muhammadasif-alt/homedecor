<?php

namespace App\Services;

use App\Mail\InquiryConfirmation;
use App\Mail\NewInquiry;
use App\Models\Inquiry;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Emails the business about a new lead and confirms receipt to the customer.
 * Mail failures are logged but never lose the lead (it is already saved).
 */
class InquiryNotifier
{
    public function notify(Inquiry $inquiry): void
    {
        $admin = Setting::get('notification_email') ?: Setting::get('email');

        $this->send(fn () => $admin && Mail::to($admin)->send(new NewInquiry($inquiry)));
        $this->send(fn () => Mail::to($inquiry->email)->send(new InquiryConfirmation($inquiry)));
    }

    private function send(callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable $e) {
            Log::warning('Inquiry email failed: '.$e->getMessage());
        }
    }
}
