<?php

namespace App\Models;

use App\Enums\TelegramServiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramServiceRequest extends Model
{
    protected $fillable = ['user_id', 'telegram_service_id', 'price', 'status', 'result', 'balance_before', 'balance_after', 'target_identifier', 'error_message'];

    protected $casts = [
        'status' => TelegramServiceStatus::class,
        'result' => 'array',
        'price' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function telegramService(): BelongsTo
    {
        return $this->belongsTo(TelegramService::class, 'telegram_service_id');
    }
}
