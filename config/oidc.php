<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Configuration Wallix OIDC
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'authentification SSO avec Wallix via OpenID Connect
    |
    */
    // 'wallix' => [
    //     'issuer' => env('WALLIX_OIDC_ISSUER', 'https://your-wallix-server.com/auth/realms/your-realm'),
    //     'client_id' => env('WALLIX_CLIENT_ID'),
    //     'client_secret' => env('WALLIX_CLIENT_SECRET'),
    //     'redirect_uri' => env('WALLIX_REDIRECT_URI', env('APP_URL') . '/auth/wallix/callback'),
    //     'scopes' => ['openid', 'profile', 'email'],
    //     'timeout' => env('WALLIX_TIMEOUT', 10), // secondes
    //     'verify_ssl' => env('WALLIX_VERIFY_SSL', true),
    // ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des tokens email
    |--------------------------------------------------------------------------
    |
    | Configuration pour le système de fallback par email
    |
    */
    'email_token' => [
        'expiry' => env('EMAIL_TOKEN_EXPIRY', 3600), // 1 heure par défaut
        'issuer' => env('EMAIL_TOKEN_ISSUER', env('APP_NAME', 'MainCourante')),
        'max_attempts' => env('EMAIL_TOKEN_MAX_ATTEMPTS', 3), 
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration générale SSO
    |--------------------------------------------------------------------------
    */
    'fallback_enabled' => env('SSO_FALLBACK_ENABLED', true),
    'wallix_health_check_url' => env('WALLIX_HEALTH_CHECK_URL'),
    'session_timeout' => env('SSO_SESSION_TIMEOUT', 28800), // 8 heures
];
