<?php

use App\Models\Setting;

if (! function_exists('currency')) {
    function currency(): string
    {
        try {
            return Setting::get('system.currency', config('app.currency', 'SAR')) ?: 'SAR';
        } catch (\Throwable $e) {
            return config('app.currency', 'SAR');
        }
    }
}

if (! function_exists('telegram_contact')) {
    function telegram_contact(): string
    {
        try {
            $fromSettings = Setting::get('purchase.telegram_contact');
            if (! empty($fromSettings)) {
                return $fromSettings;
            }
        } catch (\Throwable $e) {
            // fall through to config/env
        }

        return config('app.telegram_contact', 'https://t.me/Kh_505_p');
    }
}

if (! function_exists('format_price')) {
    function format_price(mixed $amount): string
    {
        return number_format((float) $amount, 2) . ' ' . currency();
    }
}
