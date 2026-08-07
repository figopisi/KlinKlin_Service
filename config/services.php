<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'wablas' => [
        'base_url' => env('WABLAS_BASE_URL'),
        'token'    => env('WABLAS_TOKEN'),
        'secret'   => env('WABLAS_SECRET_KEY'),
        'driver_group_id' => env('WABLAS_DRIVER_GROUP_ID'),
        'admin_group_id'  => env('WABLAS_ADMIN_GROUP_ID'),
        'cs_admins' => [
            ['nama' => 'Admin 1', 'phone' => '628xxxxxxxxxx'],
            ['nama' => 'Admin 2', 'phone' => '628xxxxxxxxxx'],
        ],
    ],

];
