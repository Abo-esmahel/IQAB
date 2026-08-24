<?php

namespace App\Models;

use App\Enums\PhoneNumberStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhoneNumber extends Model
{
    protected $fillable = ['phone_number', 'provider', 'country', 'country_code', 'provider_number_id', 'price', 'status', 'metadata', 'expires_at'];

    protected $casts = [
        'status' => PhoneNumberStatus::class,
        'metadata' => 'array',
        'price' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function numberPurchases(): HasMany
    {
        return $this->hasMany(NumberPurchase::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(NumberMessage::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', PhoneNumberStatus::Available);
    }

    public function scopeByCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }
}
