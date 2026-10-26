<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    use Sortable;

    public const MENUS = ['header' => 'Header', 'footer_quick' => 'Footer — Quick links', 'footer_legal' => 'Footer — Legal'];

    protected $fillable = ['menu', 'label', 'url', 'is_button', 'is_enabled', 'sort_order'];

    protected function casts(): array
    {
        return ['is_button' => 'boolean', 'is_enabled' => 'boolean'];
    }

    /** Relative paths are resolved against the app URL; absolute URLs pass through. */
    public function href(): string
    {
        return str_starts_with($this->url, 'http') ? $this->url : url($this->url);
    }

    /** Whether this item links to the given absolute URL, ignoring a trailing slash. */
    public function pointsTo(string $url): bool
    {
        return rtrim($this->href(), '/') === rtrim($url, '/');
    }

    public function isActive(): bool
    {
        $path = trim(parse_url($this->href(), PHP_URL_PATH) ?? '/', '/');

        return $path === '' ? request()->is('/') : request()->is($path, $path.'/*');
    }
}
