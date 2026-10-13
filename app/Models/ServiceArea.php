<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    use Sortable;

    protected $fillable = ['name', 'slug', 'summary', 'body', 'meta_title', 'meta_description', 'has_page', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['has_page' => 'boolean', 'is_published' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
