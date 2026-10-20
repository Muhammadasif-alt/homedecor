{{-- Google AdSense (Auto ads) loads only in production and only when a publisher ID is configured. --}}
@if (($pub = \App\Support\AdSense::publisherId()) && app()->isProduction())
    <meta name="google-adsense-account" content="ca-{{ $pub }}">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-{{ $pub }}" crossorigin="anonymous"></script>
@endif
