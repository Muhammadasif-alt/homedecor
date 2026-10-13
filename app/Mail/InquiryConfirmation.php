<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Models\Setting;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InquiryConfirmation extends Mailable
{
    public function __construct(public Inquiry $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We have received your message — '.Setting::get('site_name', config('app.name')));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.inquiry-confirmation', with: [
            'siteName' => Setting::get('site_name', config('app.name')),
            'phone' => Setting::get('phone'),
        ]);
    }
}
