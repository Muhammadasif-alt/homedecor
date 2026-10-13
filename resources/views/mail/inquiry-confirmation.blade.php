<x-mail::message>
# Thank you, {{ $inquiry->name }}

We have received your {{ $inquiry->type === 'consultation' ? 'consultation request' : 'message' }} and a member of our team will be in touch soon.

@if ($inquiry->service)
**Service:** {{ $inquiry->service->name }}
@endif

@if ($phone)
If your request is urgent, you can call us on {{ $phone }}.
@endif

Warm regards,<br>
{{ $siteName }}
</x-mail::message>
