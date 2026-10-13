<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewInquiry extends Mailable
{
    public function __construct(public Inquiry $inquiry) {}

    public function envelope(): Envelope
    {
        $label = $this->inquiry->type === 'consultation' ? 'consultation request' : 'contact message';

        return new Envelope(
            subject: "New {$label} from {$this->inquiry->name}",
            replyTo: [new Address($this->inquiry->email, $this->inquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.new-inquiry');
    }
}
