<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Provider
    |--------------------------------------------------------------------------
    */

    'default' => env('PAYMENT_PROVIDER', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Provider connection settings (read by PaymentGatewayService)
    |--------------------------------------------------------------------------
    */

    'gateway_url'   => env('PAYMENT_GATEWAY_URL', ''),
    'gateway_key'   => env('PAYMENT_GATEWAY_KEY', ''),
    'gateway_secret' => env('PAYMENT_GATEWAY_SECRET', ''),
    'timeout'       => env('PAYMENT_TIMEOUT', 60),

    'providers' => [
        'default' => [
            'gateway_url' => env('PAYMENT_GATEWAY_URL', ''),
            'gateway_key' => env('PAYMENT_GATEWAY_KEY', ''),
            'gateway_secret' => env('PAYMENT_GATEWAY_SECRET', ''),
        ],
    ],

];
