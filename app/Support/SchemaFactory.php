<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Builds schema.org JSON-LD nodes from real data only. Fields with no value are omitted.
 */
class SchemaFactory
{
    public function organization(): array
    {
        $local = Setting::get('local_business_enabled') && Setting::get('address');

        return array_filter([
            '@type' => $local ? 'HomeAndConstructionBusiness' : 'Organization',
            '@id' => route('home').'#organization',
            'name' => Setting::get('site_name', config('app.name')),
            'url' => route('home'),
            'description' => Setting::get('company_description'),
            'telephone' => Setting::get('phone'),
            'email' => Setting::get('email'),
            'address' => $local ? array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('address'),
                'addressLocality' => Setting::get('city'),
                'addressCountry' => Setting::get('country'),
            ]) : null,
            'sameAs' => array_values(array_filter([
                Setting::get('social_instagram'), Setting::get('social_facebook'),
                Setting::get('social_pinterest'), Setting::get('social_youtube'), Setting::get('social_tiktok'),
            ])) ?: null,
        ]);
    }

    public function service(Service $service): array
    {
        return array_filter([
            '@type' => 'Service',
            'name' => $service->name,
            'serviceType' => $service->name,
            'description' => $service->meta_description ?: $service->short_description,
            'url' => $service->url(),
            'image' => $service->heroMedia?->url(1600),
            'provider' => ['@id' => route('home').'#organization'],
            'areaServed' => Setting::get('city') ?: null,
        ]);
    }

    public function blogPosting(BlogPost $post): array
    {
        return array_filter([
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->meta_description ?: $post->excerpt,
            'image' => $post->featuredMedia?->url(1600),
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'author' => $post->author
                ? ['@type' => 'Person', 'name' => $post->author->name]
                : ['@id' => route('home').'#organization'],
            'publisher' => ['@id' => route('home').'#organization'],
            'mainEntityOfPage' => $post->url(),
        ]);
    }

    /** FAQPage only when the questions are visible on the page. */
    public function faqPage(Collection $faqs): array
    {
        return [
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($faq->answer)],
            ])->values()->all(),
        ];
    }
}
