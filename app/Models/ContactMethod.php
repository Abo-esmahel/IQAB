<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
        'icon',
        'image',
        'color',
        'url',
        'is_active',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getDisplayIconAttribute(): string
    {
        return match ($this->type) {
            'whatsapp' => '💬',
            'telegram' => '✈️',
            'email' => '📧',
            'phone' => '📞',
            'twitter' => '🐦',
            'instagram' => '📷',
            'discord' => '🎮',
            'facebook' => '👥',
            default => '🔗',
        };
    }
}
