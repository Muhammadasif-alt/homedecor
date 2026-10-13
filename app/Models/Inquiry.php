<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'scheduled' => 'Consultation Scheduled',
        'proposal' => 'Proposal Sent',
        'won' => 'Won',
        'lost' => 'Lost',
    ];

    public const PROPERTY_TYPES = [
        'apartment' => 'Apartment',
        'house' => 'House / Villa',
        'townhouse' => 'Townhouse',
        'office' => 'Home office / Studio',
        'other' => 'Other',
    ];

    public const BUDGETS = [
        'advice' => 'I need a budget recommendation',
        'essential' => 'Budget-friendly',
        'mid' => 'Mid-range',
        'premium' => 'Premium / Luxury',
    ];

    public const CONTACT_METHODS = ['phone' => 'Phone call', 'whatsapp' => 'WhatsApp', 'email' => 'Email'];

    protected $fillable = [
        'type', 'status', 'name', 'email', 'phone', 'preferred_contact', 'service_id', 'property_type',
        'location', 'budget', 'preferred_date', 'subject', 'message', 'attachments', 'consent_at',
        'source_url', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return ['attachments' => 'array', 'preferred_date' => 'date', 'consent_at' => 'datetime'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(InquiryNote::class)->latest();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
