@php
    $details = array_filter([
        'Email' => $inquiry->email,
        'Phone' => $inquiry->phone,
        'Preferred contact' => \App\Models\Inquiry::CONTACT_METHODS[$inquiry->preferred_contact] ?? $inquiry->preferred_contact,
        'Service' => $inquiry->service?->name,
        'Property type' => \App\Models\Inquiry::PROPERTY_TYPES[$inquiry->property_type] ?? $inquiry->property_type,
        'Location' => $inquiry->location,
        'Budget' => \App\Models\Inquiry::BUDGETS[$inquiry->budget] ?? $inquiry->budget,
        'Preferred date' => $inquiry->preferred_date?->format('l j F Y'),
        'Subject' => $inquiry->subject,
    ], 'filled');
@endphp

<x-admin.layout :title="'Lead: '.$inquiry->name">
    <x-admin.page-header :title="$inquiry->name" :back="route('admin.inquiries.index')"
                         :description="(\App\Http\Controllers\Admin\InquiryController::TYPES[$inquiry->type] ?? ucfirst($inquiry->type)).' · received '.$inquiry->created_at->format('j M Y, H:i')">
        <x-slot:actions>
            <x-admin.button variant="secondary" icon="chat" :href="'mailto:'.$inquiry->email">Email</x-admin.button>
            @if ($inquiry->phone)
                <x-admin.button variant="secondary" :href="'tel:'.preg_replace('/[^\d+]/', '', $inquiry->phone)">Call</x-admin.button>
            @endif
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid items-start gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Request">
                <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                    @foreach ($details as $label => $value)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-charcoal-soft">{{ $label }}</dt>
                            <dd class="mt-0.5 break-words text-sm">
                                @if ($label === 'Email')<a href="mailto:{{ $value }}" class="text-walnut hover:underline">{{ $value }}</a>
                                @else{{ $value }}@endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
                <div class="mt-6 border-t border-charcoal/10 pt-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-charcoal-soft">Message</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed">{{ $inquiry->message }}</p>
                </div>
            </x-admin.card>

            @if ($inquiry->attachments)
                <x-admin.card title="Attachments" description="Stored privately — only signed-in staff can download them.">
                    <ul class="divide-y divide-charcoal/5">
                        @foreach ($inquiry->attachments as $index => $file)
                            <li class="flex items-center justify-between gap-3 py-2">
                                <span class="flex min-w-0 items-center gap-2 text-sm">
                                    <x-admin.icon name="paperclip" class="size-4 shrink-0 text-charcoal-soft" />
                                    <span class="truncate">{{ is_array($file) ? ($file['name'] ?? basename($file['path'] ?? 'file')) : basename($file) }}</span>
                                </span>
                                <x-admin.button size="sm" variant="secondary" icon="download" :href="route('admin.inquiries.attachments', [$inquiry, $index])">Download</x-admin.button>
                            </li>
                        @endforeach
                    </ul>
                </x-admin.card>
            @endif

            <x-admin.card title="Internal notes" description="Visible to staff only.">
                <x-admin.form :action="route('admin.inquiries.notes.store', $inquiry)" class="space-y-3">
                    <x-admin.textarea name="body" label="Add a note" rows="3" placeholder="e.g. Called back, site visit booked for Tuesday." />
                    <div class="flex justify-end"><x-admin.button size="sm" icon="plus">Add note</x-admin.button></div>
                </x-admin.form>
                <ol class="mt-6 space-y-4">
                    @forelse ($inquiry->notes as $note)
                        <li class="rounded-lg border border-charcoal/10 bg-ivory/50 px-4 py-3">
                            <div class="flex items-start justify-between gap-3">
                                <p class="text-xs text-charcoal-soft">
                                    <span class="font-semibold text-charcoal">{{ $note->user?->name ?? 'Former user' }}</span>
                                    · <time datetime="{{ $note->created_at->toIso8601String() }}">{{ $note->created_at->format('j M Y, H:i') }}</time>
                                </p>
                                @if ($note->user_id === auth()->id() || auth()->user()->isAdmin())
                                    <x-admin.delete-button :action="route('admin.inquiries.notes.destroy', [$inquiry, $note])" label="Delete note" confirm="Delete this note?" />
                                @endif
                            </div>
                            <p class="mt-1 whitespace-pre-line text-sm">{{ $note->body }}</p>
                        </li>
                    @empty
                        <li class="text-sm text-charcoal-soft">No notes yet.</li>
                    @endforelse
                </ol>
            </x-admin.card>
        </div>

        <aside class="space-y-6">
            <x-admin.card title="Status">
                <x-admin.form :action="route('admin.inquiries.update', $inquiry)" method="PATCH" class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-charcoal-soft">Current</span>
                        <x-admin.status-badge :status="$inquiry->status" :label="$inquiry->statusLabel()" />
                    </div>
                    <x-admin.select name="status" label="Change status" :options="\App\Models\Inquiry::STATUSES" :value="$inquiry->status" />
                    <x-admin.button class="w-full" icon="check">Update status</x-admin.button>
                </x-admin.form>
            </x-admin.card>

            <x-admin.card title="Submission details">
                <dl class="space-y-2 text-xs text-charcoal-soft">
                    <div><dt class="font-semibold text-charcoal">Consent given</dt><dd>{{ $inquiry->consent_at?->format('j M Y, H:i') ?? '—' }}</dd></div>
                    <div><dt class="font-semibold text-charcoal">Sent from</dt><dd class="break-all">{{ $inquiry->source_url ?: '—' }}</dd></div>
                    <div><dt class="font-semibold text-charcoal">IP address</dt><dd>{{ $inquiry->ip_address ?: '—' }}</dd></div>
                    <div><dt class="font-semibold text-charcoal">Browser</dt><dd class="break-words">{{ $inquiry->user_agent ?: '—' }}</dd></div>
                </dl>
            </x-admin.card>

            <x-admin.card title="Delete lead">
                <p class="mb-3 text-sm text-charcoal-soft">Remove spam or delete personal data on request.</p>
                <x-admin.delete-button :action="route('admin.inquiries.destroy', $inquiry)" :icon-only="false" label="Delete lead"
                                       confirm="Delete this lead and its notes?" />
            </x-admin.card>
        </aside>
    </div>
</x-admin.layout>
