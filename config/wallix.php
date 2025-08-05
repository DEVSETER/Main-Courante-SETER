<?php
   return [
    'oidc_issuer' => 'https://test-main-courante.seter/connexion/sso',
    'client_id' => 'trustelem.oidc.gm2dczbzgi',
    'client_secret' => 'liIiDgyPO8CbAzvgLLkqyp5pcpUkaDen',
    'redirect_uri' => config('app.url') . '/auth/wallix/callback',
    'timeout' => 30,
    'verify_ssl' => true,
    'health_check_url' => 'https://test-main-courante.seter/connexion/sso',

    'fallback' => [
        'enabled' => true,
        'session_timeout' => 28800,
    ]
];
;
