<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    use Sortable;

    public const CATEGORIES = [
        'general' => 'General',
        'services' => 'Services',
        'pricing' => 'Pricing & Budget',
        'timeline' => 'Timelines',
        'materials' => 'Materials & Customisation',
        'booking' => 'Booking & Consultation',
    ];

    protected $fillable = ['question', 'answer', 'service_id', 'category', 'show_on_home', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['show_on_home' => 'boolean', 'is_published' => 'boolean'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
