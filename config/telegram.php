<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Telegram Service Configuration
    |--------------------------------------------------------------------------
    |
    | `api_url` and `api_token` are consumed by TelegramProviderService.
    | `bot_token` is the real Telegram Bot API token used for bot operations
    | (getMe, setWebhook, sendMessage) managed from the admin panel.
    |
    */

    'api_url'        => env('TELEGRAM_API_URL', ''),
    'api_token'      => env('TELEGRAM_API_TOKEN', ''),
    'bot_token'      => env('TELEGRAM_BOT_TOKEN', ''),
    'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET', ''),
    'timeout'              => env('TELEGRAM_TIMEOUT', 30),
    'disable_ssl_verification' => env('TELEGRAM_DISABLE_SSL_VERIFICATION', false),

];
