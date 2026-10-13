<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in · Admin · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="flex min-h-full items-center justify-center bg-charcoal px-4 py-12 font-sans antialiased">
    <main class="w-full max-w-md">
        <div class="mb-8 text-center">
            <p class="eyebrow text-sand!">Admin</p>
            <h1 class="mt-2 font-serif text-4xl text-ivory">{{ config('app.name') }}</h1>
        </div>

        <div class="rounded-lg bg-ivory p-8 shadow-xl">
            <h2 class="mb-6 font-sans text-lg font-semibold">Sign in to your account</h2>

            @if (session('success'))
                <p class="mb-4 rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-900" role="status">{{ session('success') }}</p>
            @endif

            <x-admin.form :action="route('admin.login.store')" class="space-y-5">
                <x-admin.input name="email" type="email" label="Email address" required autofocus autocomplete="username" />
                <x-admin.input name="password" type="password" label="Password" required autocomplete="current-password" />
                <x-admin.checkbox name="remember" label="Keep me signed in on this device" />
                <x-admin.button class="w-full">Sign in</x-admin.button>
            </x-admin.form>
        </div>

        <p class="mt-6 text-center text-xs text-sand/70">Authorised staff only. Sign-in attempts are rate limited.</p>
    </main>
</body>
</html>
