<?php

namespace App\Models;

use App\Enums\WebhookLogStatus;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = ['provider', 'event', 'external_id', 'payload', 'signature', 'status', 'processed_at', 'error_message'];

    protected $casts = [
        'payload' => 'array',
        'status' => WebhookLogStatus::class,
        'processed_at' => 'datetime',
    ];
}
