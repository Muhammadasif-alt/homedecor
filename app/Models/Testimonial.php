<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use Sortable;

    protected $fillable = ['customer_name', 'content', 'rating', 'service_id', 'project_id', 'photo_media_id', 'is_approved', 'sort_order'];

    protected function casts(): array
    {
        return ['is_approved' => 'boolean'];
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'photo_media_id');
    }
}
