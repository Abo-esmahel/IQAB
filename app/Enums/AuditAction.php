<?php
namespace App\Enums;

enum AuditAction: string
{
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
    case Login = 'login';
    case Logout = 'logout';
    case Purchase = 'purchase';
    case Refund = 'refund';
    case Suspend = 'suspend';
    case Activate = 'activate';
    case AdjustBalance = 'adjust_balance';

    public function label(): string
    {
        return match($this) {
            self::Create => 'Create',
            self::Update => 'Update',
            self::Delete => 'Delete',
            self::Login => 'Login',
            self::Logout => 'Logout',
            self::Purchase => 'Purchase',
            self::Refund => 'Refund',
            self::Suspend => 'Suspend',
            self::Activate => 'Activate',
            self::AdjustBalance => 'Adjust Balance',
        };
    }

}
