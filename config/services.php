<?php

return array(

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

    'github' => array(
        'token' => env('GITHUB_API_TOKEN'),
        'stats' => env('GITHUB_STATS_YEAR'),
    ),

    'umami' => array(
        'url' => env('UMAMI_URL'),
        'websiteId' => env('UMAMI_WEBSITE_ID'),
        'domains' => env('UMAMI_DOMAIN'),
    ),

    'postmark' => array(
        'key' => env('POSTMARK_API_KEY'),
    ),

    'resend' => array(
        'key' => env('RESEND_API_KEY'),
    ),

    'ses' => array(
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ),

    'slack' => array(
        'notifications' => array(
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ),
    ),

);
