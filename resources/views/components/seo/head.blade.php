@props(['seo', 'site'])

@php
    $organization = app(\App\Support\SchemaFactory::class)->organization();
    $seo->schema($organization);
    $image = $seo->imageUrl();
@endphp

<title>{{ $seo->fullTitle() }}</title>
<meta name="description" content="{{ $seo->metaDescription() }}">
<link rel="canonical" href="{{ $seo->canonicalUrl() }}">
<meta name="robots" content="{{ $seo->index && app()->isProduction() ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}">

<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:site_name" content="{{ $site['site_name'] }}">
<meta property="og:title" content="{{ $seo->fullTitle() }}">
<meta property="og:description" content="{{ $seo->metaDescription() }}">
<meta property="og:url" content="{{ $seo->canonicalUrl() }}">
<meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
@if ($image)
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:image" content="{{ $image }}">
@endif
<meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seo->fullTitle() }}">
<meta name="twitter:description" content="{{ $seo->metaDescription() }}">

@if (! empty($site['google_site_verification']))
    <meta name="google-site-verification" content="{{ $site['google_site_verification'] }}">
@endif
<meta name="theme-color" content="#F6F1EA">

@if ($json = $seo->jsonLd())
    <script type="application/ld+json">{!! $json !!}</script>
@endif
