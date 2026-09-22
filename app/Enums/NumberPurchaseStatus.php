<?php
namespace App\Enums;

enum NumberPurchaseStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case Failed = 'failed';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Cancelled => 'Cancelled',
            self::Refunded => 'Refunded',
            self::Failed => 'Failed',
        };
    }

    public function color(): string
    {
        // NOTE: only colors covered by the CSS safelist (see resources/css/app.css).
        return match($this) {
            self::Pending => 'yellow',
            self::Active => 'emerald',
            self::Expired => 'red',
            self::Cancelled => 'dark',
            self::Refunded => 'amber',
            self::Failed => 'red',
        };
    }
}
