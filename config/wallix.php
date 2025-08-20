<?php

return [
    'oidc_issuer' => 'https://test-main-courante.seter.sn/connexion/sso',
    'client_id' => 'trustelem.oidc.gm2dczbzgi',
    'client_secret' => 'liIiDgyPO8CbAzvgLLkqyp5pcpUkaDen',

    'redirect_uri' => 'https://test-main-courante.seter.sn/auth/wallix/callback',

    // URLs correctes
    'authorization_url' => 'https://test-main-courante.seter.sn/connexion/sso/auth',
    'token_url' => 'https://test-main-courante.seter.sn/connexion/sso/token',
    'userinfo_url' => 'https://test-main-courante.seter.sn/connexion/sso/userinfo',

    'timeout' => 30,
    'verify_ssl' => true,
    'health_check_url' => 'https://test-main-courante.seter.sn/connexion/sso',

    'fallback' => [
        'enabled' => true,
        'session_timeout' => 28800,
    ]
];
