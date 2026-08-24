<?php
namespace App\Enums;

enum TelegramServiceType: string
{
    case AccountLookup = 'account_lookup';
    case AccountReport = 'account_report';
    case AccountInformation = 'account_information';

    public function label(): string
    {
        return match($this) {
            self::AccountLookup => 'Account Lookup',
            self::AccountReport => 'Account Report',
            self::AccountInformation => 'Account Information',
        };
    }

}
