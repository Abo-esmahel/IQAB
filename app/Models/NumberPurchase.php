<?php

namespace App\Models;

use App\Enums\NumberPurchaseStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NumberPurchase extends Model
{
    protected $fillable = ['user_id', 'phone_number_id', 'price', 'status', 'metadata', 'purchased_at', 'expires_at', 'provider_reference', 'notified_thresholds'];

    protected $casts = [
        'status' => NumberPurchaseStatus::class,
        'metadata' => 'array',
        'notified_thresholds' => 'array',
        'price' => 'decimal:2',
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function phoneNumber(): BelongsTo
    {
        return $this->belongsTo(PhoneNumber::class);
    }
}
