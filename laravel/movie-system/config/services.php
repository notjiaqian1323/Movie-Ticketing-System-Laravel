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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],



    'movie' => [
        'url' => env('MOVIE_API_URL', 'http://localhost:8000/api/movies'),
    ],

    'review' => [
        'url' => env('REVIEW_API_URL', 'http://localhost:8000/api/reviews'),
    ],

    'account' => [
        'url' => env('ACCOUNT_API_URL', 'http://localhost:8000/api/accounts'),
    ],

    'movie_admin' => [
        'base_url' => env('MOVIE_ADMIN_API_URL', 'http://localhost:8000/api/admin'),
        'token'    => env('MOVIE_ADMIN_TOKEN'),
    ],

    'schedule' => [
        'url' => env('SCHEDULE_API_URL', 'http://localhost:8000/api/schedules'),
    ],

    'booking' => [
        'url' => env('BOOKING_API_URL', 'http://localhost:8000/api/booking'),
    ],

];
