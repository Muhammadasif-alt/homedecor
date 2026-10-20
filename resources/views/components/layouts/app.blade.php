<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo.head :seo="$seo" :site="$site" />
    <x-seo.adsense />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Manrope:wght@400;500;600;700&display=swap">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-charcoal focus:px-5 focus:py-3 focus:text-ivory">Skip to content</a>

    <x-site.header :site="$site" :items="$menus['header'] ?? collect()" :services="$menuServices" />

    <main id="main">
        {{ $slot }}
    </main>

    <x-site.footer :site="$site" :menus="$menus" :services="$menuServices" :areas="$serviceAreas" />
    <x-site.whatsapp-float :site="$site" />

    <x-seo.analytics :id="$site['ga_measurement_id'] ?? null" />
</body>
</html>
