@props(['site'])

@php $number = preg_replace('/\D/', '', $site['whatsapp'] ?? ''); @endphp

@if ($number && ($site['whatsapp_float_enabled'] ?? true))
    <a href="https://wa.me/{{ $number }}?text={{ rawurlencode('Hello, I would like to ask about your home decor services.') }}"
       target="_blank" rel="noopener"
       class="fixed bottom-5 right-5 z-40 grid size-14 place-items-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105"
       aria-label="Chat with us on WhatsApp">
        <x-icon name="whatsapp" size="28" stroke="1.7" />
    </a>
@endif
