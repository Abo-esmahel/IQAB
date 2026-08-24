<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NumberMessage extends Model
{
    protected $fillable = ['phone_number_id', 'provider_message_id', 'user_id', 'sender', 'message', 'is_read', 'received_at', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
        'received_at' => 'datetime',
    ];

    public function phoneNumber(): BelongsTo
    {
        return $this->belongsTo(PhoneNumber::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
