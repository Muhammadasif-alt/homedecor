<x-mail::message>
# New {{ $inquiry->type === 'consultation' ? 'consultation request' : 'contact message' }}

<x-mail::table>
| | |
|:--|:--|
| **Name** | {{ $inquiry->name }} |
| **Email** | {{ $inquiry->email }} |
@if ($inquiry->phone)
| **Phone** | {{ $inquiry->phone }} |
@endif
@if ($inquiry->preferred_contact)
| **Preferred contact** | {{ \App\Models\Inquiry::CONTACT_METHODS[$inquiry->preferred_contact] ?? $inquiry->preferred_contact }} |
@endif
@if ($inquiry->service)
| **Service** | {{ $inquiry->service->name }} |
@endif
@if ($inquiry->property_type)
| **Property** | {{ \App\Models\Inquiry::PROPERTY_TYPES[$inquiry->property_type] ?? $inquiry->property_type }} |
@endif
@if ($inquiry->location)
| **Location** | {{ $inquiry->location }} |
@endif
@if ($inquiry->budget)
| **Budget** | {{ \App\Models\Inquiry::BUDGETS[$inquiry->budget] ?? $inquiry->budget }} |
@endif
@if ($inquiry->preferred_date)
| **Preferred date** | {{ $inquiry->preferred_date->format('j M Y') }} |
@endif
@if ($inquiry->attachments)
| **Photos** | {{ count($inquiry->attachments) }} attached (view in admin) |
@endif
</x-mail::table>

**Message**

{{ $inquiry->message }}

<x-mail::button :url="url('/admin/inquiries/'.$inquiry->id)">
Open in admin
</x-mail::button>
</x-mail::message>
