@props(['id' => null])

{{-- Google Analytics loads only in production and only when a measurement ID is configured. --}}
@if ($id && app()->isProduction() && preg_match('/^G-[A-Z0-9]+$/', $id))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $id }}', { anonymize_ip: true });
    </script>
@endif
