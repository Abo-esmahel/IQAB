<?php

namespace App\Models;

use App\Enums\TelegramServiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramService extends Model
{
    protected $fillable = ['name', 'type', 'description', 'price', 'is_active'];

    protected $casts = [
        'type' => TelegramServiceType::class,
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function telegramServiceRequests(): HasMany
    {
        return $this->hasMany(TelegramServiceRequest::class);
    }
}
