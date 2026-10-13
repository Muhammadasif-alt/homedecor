<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy($this->getTable().'.sort_order')->orderBy($this->getTable().'.id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where($this->getTable().'.is_published', true);
    }
}
