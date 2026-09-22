<?php
namespace App\Enums;

enum PhoneNumberStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Active = 'active';
    case Expired = 'expired';
    case Disabled = 'disabled';

    public function label(): string
    {
        return match($this) {
            self::Available => 'Available',
            self::Reserved => 'Reserved',
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Disabled => 'Disabled',
        };
    }

    public function color(): string
    {
        // NOTE: only colors covered by the CSS safelist (see resources/css/app.css).
        return match($this) {
            self::Available => 'emerald',
            self::Reserved => 'yellow',
            self::Active => 'primary',
            self::Expired => 'red',
            self::Disabled => 'dark',
        };
    }

}
