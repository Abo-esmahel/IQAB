<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Phone Provider
    |--------------------------------------------------------------------------
    */

    'default' => env('PHONE_PROVIDER', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Provider connection settings (read by PhoneProviderService)
    |--------------------------------------------------------------------------
    */

    'base_url'       => env('PHONE_PROVIDER_URL', ''),
    'token'          => env('PHONE_PROVIDER_TOKEN', ''),
    'webhook_secret' => env('PHONE_PROVIDER_WEBHOOK_SECRET', ''),
    'timeout'        => env('PHONE_PROVIDER_TIMEOUT', 30),

    'providers' => [
        'default' => [
            'base_url' => env('PHONE_PROVIDER_URL', ''),
            'token' => env('PHONE_PROVIDER_TOKEN', ''),
            'webhook_secret' => env('PHONE_PROVIDER_WEBHOOK_SECRET', ''),
        ],
    ],

];
