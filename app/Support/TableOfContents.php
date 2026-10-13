<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Ensures every <h2> in article HTML has an id and returns a list for the table of contents.
 */
class TableOfContents
{
    /** @return array{0:string, 1:array<int, array{id:string, text:string}>} */
    public static function build(string $html): array
    {
        $toc = [];
        $used = [];

        $html = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/is', function ($m) use (&$toc, &$used) {
            $text = trim(html_entity_decode(strip_tags($m[2])));
            preg_match('/\bid="([^"]+)"/i', $m[1], $existing);
            $id = $existing[1] ?? Str::slug($text);
            $base = $id;
            $i = 2;
            while (in_array($id, $used, true)) {
                $id = $base.'-'.$i++;
            }
            $used[] = $id;
            $toc[] = ['id' => $id, 'text' => $text];
            $attrs = $existing ? preg_replace('/\bid="[^"]+"/i', 'id="'.$id.'"', $m[1]) : $m[1].' id="'.$id.'"';

            return "<h2{$attrs}>{$m[2]}</h2>";
        }, $html);

        return [$html, count($toc) >= 3 ? $toc : []];
    }
}
