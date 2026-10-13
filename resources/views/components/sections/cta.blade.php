@props(['section', 'data' => [], 'first' => false])

<x-cta-banner :media="$section->media" :eyebrow="$section->eyebrow" :heading="$section->heading" :body="strip_tags($section->body)"
              :buttons="$section->get('buttons', [['label' => 'Book a Consultation', 'url' => '/book-consultation']])" />
