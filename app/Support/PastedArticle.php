<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Support\Str;

/**
 * Turns an article pasted in the admin (Markdown, as the owner's briefs are written) into article
 * HTML. "[IMAGE: description]" lines become photo slots: the first is the featured photo, the rest
 * are placed in the body where they appeared. Section headings become <h2> with stable ids, an
 * "FAQ" or "Frequently Asked Questions" heading becomes <h2 id="faq">, and bold numbered questions
 * become <h3> so the FAQ reads like the rest of the site.
 */
class PastedArticle
{
    private const SLOT = '@@SLOT%d@@';

    /**
     * @param  array<int, string>  $images  Image descriptions in order; index 0 is the featured photo.
     */
    private function __construct(
        public readonly string $title,
        public readonly array $images,
        private readonly string $markdown,
    ) {}

    public static function parse(string $text): self
    {
        $title = '';
        $images = [];
        $lines = [];

        foreach (preg_split('/\R/u', trim($text)) as $line) {
            $trimmed = trim($line);

            if (preg_match('/^\[IMAGE:\s*(.*?)\]$/iu', $trimmed, $m)) {
                $lines[] = '';
                $lines[] = sprintf(self::SLOT, count($images));
                $lines[] = '';
                $images[] = $m[1];

                continue;
            }

            if ($title === '' && preg_match('/^#\s+(.+)$/u', $trimmed, $m)) {
                $title = trim($m[1]);

                continue;
            }

            if (preg_match('/^(\*\s*){3,}$|^(-\s*){3,}$|^(_\s*){3,}$/u', $trimmed)) {
                continue;
            }

            if (preg_match('/^#{2,6}\s+(.+?)\s*#*$/u', $trimmed, $m)) {
                $heading = trim($m[1]);
                $lines[] = match (true) {
                    (bool) preg_match('/^(frequently asked questions|faqs?)$/iu', $heading) => '## FAQ',
                    str_ends_with($heading, '?') => '### '.$heading,
                    default => '## '.$heading,
                };

                continue;
            }

            if (preg_match('/^\*\*\s*(?:\d+[.)]\s*)?(.+?\?)\s*\*\*\s*(.*)$/u', $trimmed, $m)) {
                $lines[] = '### '.$m[1];
                if (filled($m[2])) {
                    $lines[] = '';
                    $lines[] = $m[2];
                }

                continue;
            }

            $lines[] = $line;
        }

        $markdown = implode("\n", $lines);
        if ($title === '') {
            $title = Str::limit(trim(strip_tags(Str::markdown(strtok($markdown, "\n") ?: ''))), 120, '');
        }

        return new self($title, $images, $markdown);
    }

    /**
     * Article HTML with each body photo slot filled. Slot 0 is the featured photo and is never
     * repeated in the body; slots without a photo are dropped.
     *
     * @param  array<int, array{media: ?Media, caption: ?string}>  $slots
     */
    public function html(array $slots = []): string
    {
        $html = Str::markdown($this->markdown, ['html_input' => 'strip', 'allow_unsafe_links' => false]);

        $html = preg_replace_callback('/<p>@@SLOT(\d+)@@<\/p>/', function ($m) use ($slots) {
            $index = (int) $m[1];
            $media = $slots[$index]['media'] ?? null;

            return $index === 0 || ! $media ? '' : ArticleFigure::html($media, $slots[$index]['caption'] ?? null);
        }, $html);

        $used = [];

        return preg_replace_callback('/<h2>(.*?)<\/h2>/s', function ($m) use (&$used) {
            $text = trim(html_entity_decode(strip_tags($m[1])));
            $id = match (true) {
                $text === 'FAQ' => 'faq',
                (bool) preg_match('/^final thoughts$/iu', $text) => 'final-thoughts',
                default => Str::slug(preg_replace('/^\d+[.)]\s*/', '', $text)) ?: 'section',
            };
            $base = $id;
            for ($i = 2; in_array($id, $used, true); $i++) {
                $id = $base.'-'.$i;
            }
            $used[] = $id;

            return '<h2 id="'.$id.'">'.$m[1].'</h2>';
        }, $html);
    }

    /** The opening paragraph as plain text, cut to a sentence or word boundary. */
    public function excerpt(int $limit = 300): string
    {
        preg_match('/<p>(.*?)<\/p>/s', $this->html(), $m);

        return self::shorten(trim(html_entity_decode(strip_tags($m[1] ?? ''))), $limit);
    }

    public function metaDescription(): string
    {
        return $this->excerpt(160);
    }

    private static function shorten(string $text, int $limit): string
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $cut = mb_substr($text, 0, $limit);
        $sentence = max(mb_strrpos($cut, '. ') ?: 0, mb_strrpos($cut, '? ') ?: 0, mb_strrpos($cut, '! ') ?: 0);
        if ($sentence > $limit / 2) {
            return mb_substr($cut, 0, $sentence + 1);
        }

        return rtrim(mb_substr($cut, 0, mb_strrpos($cut, ' ') ?: $limit - 1), ' ,;:-').'…';
    }
}
