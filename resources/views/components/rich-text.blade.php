{{--
    Renders CMS HTML (sanitised on save in the admin; seeded content is trusted).
    Plain text with blank lines is converted to paragraphs.
--}}
@props(['html' => ''])

@php
    $content = (string) $html;
    if ($content !== '' && ! str_contains($content, '<')) {
        $content = collect(preg_split('/\n\s*\n/', e($content)))->map(fn ($p) => '<p>'.nl2br(trim($p)).'</p>')->implode('');
    }
@endphp

@if ($content !== '')
    <div {{ $attributes->merge(['class' => 'prose-decor']) }}>{!! $content !!}</div>
@endif
