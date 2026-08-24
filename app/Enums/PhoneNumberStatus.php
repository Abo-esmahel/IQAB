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
        return match($this) {
            self::Available => 'green',
            self::Reserved => 'yellow',
            self::Active => 'blue',
            self::Expired => 'red',
            self::Disabled => 'gray',
        };
    }

}
