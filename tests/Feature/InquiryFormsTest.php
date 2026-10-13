<?php

namespace Tests\Feature;

use App\Mail\InquiryConfirmation;
use App\Mail\NewInquiry;
use App\Models\Inquiry;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class InquiryFormsTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();
        Mail::fake();
    }

    private function validContact(array $overrides = []): array
    {
        return $overrides + [
            'name' => 'Sara Khan', 'email' => 'sara@example.com', 'message' => 'I would like help with my lounge.',
            'consent' => '1', 'form_started' => encrypt(now()->subMinute()->timestamp),
        ];
    }

    public function test_contact_form_stores_lead_and_sends_emails(): void
    {
        Setting::put(['notification_email' => 'studio@example.com']);

        $this->post('/contact', $this->validContact())->assertRedirect('/thank-you');

        $this->assertDatabaseHas('inquiries', ['type' => 'contact', 'status' => 'new', 'email' => 'sara@example.com']);
        Mail::assertSent(NewInquiry::class, fn ($mail) => $mail->hasTo('studio@example.com'));
        Mail::assertSent(InquiryConfirmation::class, fn ($mail) => $mail->hasTo('sara@example.com'));
        $this->get('/thank-you')->assertOk();
        $this->get('/thank-you')->assertNotFound(); // only reachable straight after a submission
    }

    public function test_honeypot_and_instant_submissions_are_rejected(): void
    {
        $this->post('/contact', $this->validContact(['website' => 'http://spam.test']))->assertSessionHasErrors('website');
        $this->post('/contact', $this->validContact(['form_started' => encrypt(now()->timestamp)]))->assertSessionHasErrors('form');
        $this->post('/contact', $this->validContact(['form_started' => 'tampered']))->assertSessionHasErrors('form');

        $this->assertSame(0, Inquiry::count());
    }

    public function test_consent_is_required(): void
    {
        $this->post('/contact', $this->validContact(['consent' => null]))->assertSessionHasErrors('consent');
    }

    public function test_consultation_requires_phone_and_contact_method_and_stores_private_attachments(): void
    {
        Storage::fake('local');
        $service = $this->service();

        $this->post('/book-consultation', $this->validContact())
            ->assertSessionHasErrors(['phone', 'preferred_contact']);

        $this->post('/book-consultation', $this->validContact([
            'phone' => '+92 300 1234567', 'preferred_contact' => 'whatsapp', 'service_id' => $service->id,
            'property_type' => 'apartment', 'budget' => 'mid', 'preferred_date' => now()->addWeek()->toDateString(),
            'attachments' => [UploadedFile::fake()->image('lounge.jpg', 800, 600)],
        ]))->assertRedirect('/thank-you');

        $inquiry = Inquiry::sole();
        $this->assertSame('consultation', $inquiry->type);
        $this->assertSame($service->id, $inquiry->service_id);
        Storage::disk('local')->assertExists($inquiry->attachments[0]['path']);
    }

    public function test_non_image_attachments_are_rejected(): void
    {
        $this->post('/book-consultation', $this->validContact([
            'phone' => '0300 1234567', 'preferred_contact' => 'phone',
            'attachments' => [UploadedFile::fake()->create('virus.php', 10, 'application/x-php')],
        ]))->assertSessionHasErrors('attachments.0');
    }

    public function test_forms_are_rate_limited(): void
    {
        foreach (range(1, 5) as $i) {
            $this->post('/contact', $this->validContact(['email' => "user{$i}@example.com"]));
        }

        $this->post('/contact', $this->validContact())->assertStatus(429);
    }
}
