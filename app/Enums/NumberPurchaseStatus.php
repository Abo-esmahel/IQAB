<?php
namespace App\Enums;

enum NumberPurchaseStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Cancelled => 'Cancelled',
            self::Refunded => 'Refunded',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Active => 'green',
            self::Expired => 'red',
            self::Cancelled => 'gray',
            self::Refunded => 'orange',
        };
    }

}
