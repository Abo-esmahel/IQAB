<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    private const RESERVED_CONFIG_KEYS = [
        'app', 'auth', 'cache', 'database', 'filesystems', 'logging', 'mail',
        'queue', 'services', 'session', 'view', 'livewire', 'horizon', 'passport',
        'scout', 'cashier', 'broadcasting',
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            $settings = Setting::allCached();
        } catch (\Throwable $e) {
            return;
        }

        foreach ($settings as $key => $row) {
            if (! is_array($row) || empty($row['key'])) {
                continue;
            }

            $topLevel = explode('.', $row['key'])[0];
            if (in_array($topLevel, self::RESERVED_CONFIG_KEYS, true)) {
                continue;
            }

            try {
                $value = Setting::castValue($row['value'], $row['type']);
                config([$row['key'] => $value]);
            } catch (\Throwable $e) {
                //
            }
        }
    }
}
