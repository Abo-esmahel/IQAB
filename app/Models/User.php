<?php

namespace App\Models;

use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'balance' => 'decimal:2',
        'status' => UserStatus::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(function (User $user) {
            Wallet::create(['user_id' => $user->id, 'balance' => 0, 'total_deposited' => 0, 'total_spent' => 0]);
        });
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        $status = $this->attributes['status'] ?? $this->status;

        if ($status instanceof UserStatus) {
            return $status === UserStatus::Active;
        }

        return $status === UserStatus::Active->value;
    }

    public function canAccess(string $permission): bool
    {
        return $this->isAdmin() || $this->hasPermission($permission);
    }

    protected function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function numberPurchases(): HasMany
    {
        return $this->hasMany(NumberPurchase::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(NumberMessage::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function telegramServiceRequests(): HasMany
    {
        return $this->hasMany(TelegramServiceRequest::class);
    }

    public function servicePurchases(): HasMany
    {
        return $this->hasMany(ServicePurchase::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable_id');
    }
}
