<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google OAuth Configuration
    |--------------------------------------------------------------------------
    */

    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_REDIRECT_URI'),

    /*
    |--------------------------------------------------------------------------
    | OAuth Scopes
    |--------------------------------------------------------------------------
    | The scopes requested during OAuth authorization.
    */

    'scopes' => [
        'openid',
        'email',
        'profile',
        'https://www.googleapis.com/auth/analytics.readonly',
        'https://www.googleapis.com/auth/webmasters.readonly',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */

    'token_url' => 'https://oauth2.googleapis.com/token',
    'revoke_url' => 'https://oauth2.googleapis.com/revoke',
    'userinfo_url' => 'https://www.googleapis.com/oauth2/v2/userinfo',

    // GA4 Data API
    'ga4_api_base' => 'https://analyticsdata.googleapis.com/v1beta',
    'ga4_admin_api_base' => 'https://analyticsadmin.googleapis.com/v1beta',

    // Search Console API
    'gsc_api_base' => 'https://www.googleapis.com/webmasters/v3',
];
