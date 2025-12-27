<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Keys
    |--------------------------------------------------------------------------
    |
    | The Stripe publishable key and secret key give you access to Stripe's
    | API. These keys identify your Stripe account.
    |
    */

    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Price ID
    |--------------------------------------------------------------------------
    |
    | The price ID for the subscription product ($9.99/month per website).
    | Create this in your Stripe dashboard first.
    |
    */

    'price_id' => env('STRIPE_PRICE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Trial Period
    |--------------------------------------------------------------------------
    |
    | Number of days for free trial per website.
    |
    */

    'trial_days' => 7,

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */

    'currency' => 'usd',
];
