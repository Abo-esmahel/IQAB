<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Wallet Settings
    |--------------------------------------------------------------------------
    |
    | Configure wallet limits and defaults for the IQAB platform.
    |
    */

    'min_deposit' => (int) env('WALLET_MIN_DEPOSIT', 1),
    'max_deposit' => (int) env('WALLET_MAX_DEPOSIT', 10000),
    'currency' => env('WALLET_CURRENCY', 'SAR'),

];
