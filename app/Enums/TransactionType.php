<?php
namespace App\Enums;

enum TransactionType: string
{
    case Deposit = 'deposit';
    case Purchase = 'purchase';
    case Refund = 'refund';
    case Adjustment = 'adjustment';
    case ServiceCharge = 'service_charge';

    public function label(): string
    {
        return match($this) {
            self::Deposit => 'Deposit',
            self::Purchase => 'Purchase',
            self::Refund => 'Refund',
            self::Adjustment => 'Adjustment',
            self::ServiceCharge => 'Service Charge',
        };
    }

}
