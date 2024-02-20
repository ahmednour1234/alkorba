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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => '948857713630-3l9e56id5708g1epqgr5n3hjh00mftbc.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://127.0.0.1:8000/api/google/callback',
    ],
    'facebook' => [
        'client_id' => env('750888000294675'),
        'client_secret' => env('3a4f67209df5e0f856adc87335649d5a'),
        'redirect' => env('http://127.0.0.1:8000/api/facebook/callback'),
    ],
    'vonage' => [
        'key' => env('8dc89a0c'),
        'secret' => env('rUnVuE7jafgHmYuZ'),
        'sms_from' => env('201288817121'),
    ],



];
