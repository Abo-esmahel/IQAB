<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePurchase extends Model
{
    protected $fillable = [
        'user_id',
        'market_service_id',
        'price',
        'status',
        'input_data',
        'result',
        'error_message',
        'balance_before',
        'balance_after',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'result' => 'array',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketService()
    {
        return $this->belongsTo(MarketService::class);
    }
}
