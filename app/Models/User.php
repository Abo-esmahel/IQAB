<?php

namespace App\Models;

use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'status' => UserStatus::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function canAccess(string $permission): bool
    {
        return $this->isAdmin();
    }

    public function numberPurchases(): HasMany
    {
        return $this->hasMany(NumberPurchase::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(NumberMessage::class);
    }

    public function telegramServiceRequests(): HasMany
    {
        return $this->hasMany(TelegramServiceRequest::class);
    }

    public function servicePurchases(): HasMany
    {
        return $this->hasMany(ServicePurchase::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notifiable_id');
    }
}
