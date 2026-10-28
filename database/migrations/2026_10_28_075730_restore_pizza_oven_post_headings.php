<?php

use App\Models\BlogPost;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

/**
 * Both pizza oven articles were saved once through the visual editor, which cannot hold <h2>, <h3>
 * or <p>: every heading became a bold line inside a <div>, so the articles lost their headings and
 * table of contents and the photo migration found no sections to place photos under. Turns the
 * bold-only lines back into headings and the divs back into paragraphs, then re-runs that migration.
 */
return new class extends Migration
{
    private const SLUGS = ['how-to-build-an-outdoor-pizza-oven', 'outdoor-pizza-oven-pros-and-cons'];

    public function up(): void
    {
        foreach (BlogPost::whereIn('slug', self::SLUGS)->get() as $post) {
            if (! str_contains($post->body, '<h2') && str_contains($post->body, '<div>')) {
                $post->update(['body' => $this->restore($post->body)]);
            }
        }

        (require __DIR__.'/2026_10_28_075254_add_photos_and_seo_to_pizza_oven_posts.php')->up();
    }

    private function restore(string $html): string
    {
        $blocks = [];
        foreach (explode('</div>', $html) as $chunk) {
            $inner = preg_replace('/^(\s|<br>)+|(\s|<br>)+$/', '', preg_replace('/^\s*<div>/', '', $chunk));
            if ($inner === '') {
                continue;
            }

            if (preg_match('#^<strong>(?:<br>)*([^<]+?)(?:<br>)*</strong>$#', $inner, $match)) {
                $blocks[] = $this->heading(trim($match[1]));

                continue;
            }

            $inner = str_replace('<strong><br>', '<strong>', $inner);
            foreach (preg_split('/(?:\s*<br>\s*){2,}/', $inner) as $paragraph) {
                if (trim($paragraph) !== '') {
                    $blocks[] = '<p>'.trim($paragraph).'</p>';
                }
            }
        }

        return implode("\n", $blocks);
    }

    private function heading(string $text): string
    {
        if (str_ends_with($text, '?')) {
            return "<h3>{$text}</h3>";
        }

        $id = strcasecmp($text, 'FAQ') === 0 ? 'faq' : Str::slug(preg_replace('/^\d+\.\s*/', '', $text));

        return "<h2 id=\"{$id}\">{$text}</h2>";
    }

    public function down(): void
    {
        //
    }
};
