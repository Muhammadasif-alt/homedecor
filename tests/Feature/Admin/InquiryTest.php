<?php

namespace Tests\Feature\Admin;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Storage;

class InquiryTest extends AdminTestCase
{
    private function inquiry(array $attributes = []): Inquiry
    {
        return Inquiry::create($attributes + [
            'type' => 'consultation', 'status' => 'new', 'name' => 'Amira Haddad',
            'email' => 'amira@example.org', 'message' => 'Living room refresh please.',
        ]);
    }

    public function test_status_can_be_updated_and_notes_added(): void
    {
        $editor = $this->editor();
        $inquiry = $this->inquiry();

        $this->actingAs($editor)->get(route('admin.inquiries.index'))->assertOk()->assertSee('Amira Haddad');
        $this->actingAs($editor)->get(route('admin.inquiries.show', $inquiry))->assertOk();

        $this->actingAs($editor)->patch(route('admin.inquiries.update', $inquiry), ['status' => 'contacted'])
            ->assertRedirect(route('admin.inquiries.show', $inquiry));
        $this->assertSame('contacted', $inquiry->fresh()->status);

        $this->actingAs($editor)->patch(route('admin.inquiries.update', $inquiry), ['status' => 'bogus'])
            ->assertSessionHasErrors('status');

        $this->actingAs($editor)->post(route('admin.inquiries.notes.store', $inquiry), ['body' => 'Called back, visit on Tuesday.'])
            ->assertRedirect(route('admin.inquiries.show', $inquiry));

        $this->assertDatabaseHas('inquiry_notes', [
            'inquiry_id' => $inquiry->id, 'user_id' => $editor->id, 'body' => 'Called back, visit on Tuesday.',
        ]);
        $this->actingAs($editor)->get(route('admin.inquiries.show', $inquiry))->assertSee('Called back, visit on Tuesday.');
    }

    public function test_filters_by_type_and_status(): void
    {
        $this->inquiry(['name' => 'Consult Person']);
        $this->inquiry(['name' => 'Contact Person', 'type' => 'contact', 'status' => 'won']);

        $this->actingAs($this->editor())->get(route('admin.inquiries.index', ['type' => 'contact']))
            ->assertSee('Contact Person')->assertDontSee('Consult Person');
    }

    public function test_attachments_download_only_for_staff(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('inquiries/2026/10/room.jpg', 'jpeg-bytes');
        $inquiry = $this->inquiry(['attachments' => [['path' => 'inquiries/2026/10/room.jpg', 'name' => 'room.jpg']]]);

        $this->get(route('admin.inquiries.attachments', [$inquiry, 0]))->assertRedirect(route('admin.login'));

        $this->actingAs($this->editor())->get(route('admin.inquiries.attachments', [$inquiry, 0]))
            ->assertOk()->assertDownload('room.jpg');
        $this->actingAs($this->editor())->get(route('admin.inquiries.attachments', [$inquiry, 5]))->assertNotFound();
    }

    public function test_inquiry_is_soft_deleted(): void
    {
        $inquiry = $this->inquiry();

        $this->actingAs($this->editor())->delete(route('admin.inquiries.destroy', $inquiry))
            ->assertRedirect(route('admin.inquiries.index'));
        $this->assertSoftDeleted($inquiry);
    }
}
