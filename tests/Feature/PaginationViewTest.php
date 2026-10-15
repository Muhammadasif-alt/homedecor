<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

/** The default paginator shows a five-page window with an ellipsis and the first/last page. */
class PaginationViewTest extends TestCase
{
    private function pages(int $current): array
    {
        $html = (new LengthAwarePaginator(range(1, 10), 100, 10, $current, ['path' => '/blog']))->links()->toHtml();
        preg_match_all('/>\s*(\d+|&hellip;)\s*</', $html, $m);

        return $m[1];
    }

    public function test_window_starts_with_first_five_pages_then_last(): void
    {
        $this->assertSame(['1', '2', '3', '4', '5', '&hellip;', '10'], $this->pages(1));
    }

    public function test_window_follows_the_current_page(): void
    {
        $this->assertSame(['1', '&hellip;', '4', '5', '6', '7', '8', '&hellip;', '10'], $this->pages(6));
        $this->assertSame(['1', '&hellip;', '6', '7', '8', '9', '10'], $this->pages(10));
    }
}
