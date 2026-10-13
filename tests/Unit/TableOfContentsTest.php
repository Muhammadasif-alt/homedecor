<?php

namespace Tests\Unit;

use App\Support\TableOfContents;
use PHPUnit\Framework\TestCase;

class TableOfContentsTest extends TestCase
{
    public function test_it_adds_unique_ids_and_keeps_existing_ones(): void
    {
        [$html, $toc] = TableOfContents::build('<h2 id="intro">Intro</h2><h2>Colour &amp; Light</h2><h2>Colour &amp; Light</h2>');

        $this->assertSame(['intro', 'colour-light', 'colour-light-2'], array_column($toc, 'id'));
        $this->assertStringContainsString('<h2 id="colour-light-2">', $html);
        $this->assertSame('Colour & Light', $toc[1]['text']);
    }

    public function test_short_articles_get_no_table_of_contents(): void
    {
        [, $toc] = TableOfContents::build('<h2>One</h2><h2>Two</h2>');

        $this->assertSame([], $toc);
    }
}
