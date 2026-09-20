<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'badge',
        'original_price',
        'offer_price',
        'discount_percent',
        'type',
        'related_service_id',
        'related_number_id',
        'cta_text',
        'cta_url',
        'starts_at',
        'expires_at',
        'is_active',
        'is_featured',
        'sort_order',
        'usage_limit',
        'used_count',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType(Builder $query, ?string $type): Builder
    {
        return $type ? $query->where('type', $type) : $query;
    }

    public function relatedService(): BelongsTo
    {
        return $this->belongsTo(MarketService::class, 'related_service_id');
    }

    public function relatedNumber(): BelongsTo
    {
        return $this->belongsTo(PhoneNumber::class, 'related_number_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDiscountTextAttribute(): ?string
    {
        if ($this->discount_percent) {
            return number_format($this->discount_percent, 0) . '% OFF';
        }
        if ($this->original_price && $this->offer_price) {
            $saved = $this->original_price - $this->offer_price;
            if ($saved > 0) {
                return number_format($saved, 2) . ' '.currency().' OFF';
            }
        }
        return null;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsStartedAttribute(): bool
    {
        return !$this->starts_at || $this->starts_at->isPast();
    }

    public function getIsActiveNowAttribute(): bool
    {
        return $this->is_active && $this->isStarted && !$this->isExpired;
    }
}
