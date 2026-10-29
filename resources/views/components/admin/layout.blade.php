@props(['title' => 'Dashboard'])

@php
    $siteName = config('app.name', 'Home Decor Studio');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="media-library-url" content="{{ route('admin.media.library') }}">
    <meta name="media-upload-url" content="{{ route('admin.media.store') }}">
    <meta name="media-import-url" content="{{ route('admin.media.import-url') }}">
    <title>{{ $title }} · Admin · {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="h-full bg-ivory font-sans text-charcoal antialiased" x-data="{ nav: false }" x-on:keydown.escape.window="nav = false">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded focus:bg-white focus:px-4 focus:py-2">Skip to content</a>

    {{-- Mobile drawer backdrop --}}
    <div x-show="nav" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-charcoal/60 lg:hidden" x-on:click="nav = false"></div>

    <x-admin.sidebar />

    <div class="lg:pl-64">
        <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-charcoal/10 bg-ivory/90 px-4 backdrop-blur sm:px-6 lg:px-10">
            <button type="button" class="-ml-1 rounded p-2 text-charcoal hover:bg-ivory-dark focus-visible:outline-walnut lg:hidden"
                    x-on:click="nav = true" aria-label="Open navigation">
                <x-admin.icon name="bars" />
            </button>
            <p class="truncate text-sm font-medium text-charcoal-soft">{{ $title }}</p>

            <div class="ml-auto flex items-center gap-2 sm:gap-4">
                <a href="{{ url('/') }}" target="_blank" rel="noopener"
                   class="hidden items-center gap-1.5 rounded px-2 py-1.5 text-sm text-charcoal-soft hover:text-walnut sm:inline-flex">
                    <x-admin.icon name="external" class="size-4" /> View site
                </a>
                <span class="hidden text-sm text-charcoal-soft md:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded border border-charcoal/15 bg-white px-3 py-1.5 text-sm font-medium hover:border-walnut hover:text-walnut">
                        <x-admin.icon name="logout" class="size-4" /> <span class="hidden sm:inline">Sign out</span>
                    </button>
                </form>
            </div>
        </header>

        <main id="main" class="px-4 py-8 sm:px-6 lg:px-10">
            <div class="mx-auto max-w-6xl space-y-6">
                <x-admin.flash />
                {{ $slot }}
            </div>
        </main>
    </div>

    <x-admin.media-modal />
</body>
</html>
