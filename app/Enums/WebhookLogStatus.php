<?php
namespace App\Enums;

enum WebhookLogStatus: string
{
    case Received = 'received';
    case Processed = 'processed';
    case Failed = 'failed';
    case Ignored = 'ignored';

    public function label(): string
    {
        return match($this) {
            self::Received => 'Received',
            self::Processed => 'Processed',
            self::Failed => 'Failed',
            self::Ignored => 'Ignored',
        };
    }

}
