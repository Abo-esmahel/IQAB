<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class Notification extends BaseDatabaseNotification
{
    protected $fillable = ['type', 'notifiable_type', 'notifiable_id', 'data', 'read_at'];

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }
}
