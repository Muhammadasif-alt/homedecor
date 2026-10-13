{{--
    Responsive image with WebP + JPEG fallback and intrinsic dimensions (prevents layout shift).
    Usage: <x-picture :media="$media" sizes="(min-width: 1024px) 50vw, 100vw" class="aspect-[4/3]" />
    Set :priority="true" for above-the-fold images (eager + high fetch priority).
--}}
@props(['media', 'sizes' => '100vw', 'priority' => false, 'alt' => null, 'imgClass' => 'h-full w-full object-cover'])

@if ($media)
    <picture {{ $attributes }}>
        <source type="image/webp" srcset="{{ $media->srcset('webp') }}" sizes="{{ $sizes }}">
        <img src="{{ $media->url(960) }}"
             srcset="{{ $media->srcset('jpg') }}"
             sizes="{{ $sizes }}"
             width="{{ $media->width }}" height="{{ $media->height }}"
             alt="{{ $alt ?? $media->alt }}"
             class="{{ $imgClass }}"
             @if ($priority) fetchpriority="high" loading="eager" @else loading="lazy" @endif
             decoding="async">
    </picture>
@endif
