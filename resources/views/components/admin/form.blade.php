@props(['action', 'method' => 'POST', 'files' => false])

{{-- Form shell: CSRF token + method spoofing. --}}
<form method="POST" action="{{ $action }}" @if ($files) enctype="multipart/form-data" @endif
      {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @csrf
    @unless (strtoupper($method) === 'POST')
        @method($method)
    @endunless
    {{ $slot }}
</form>
