<?php
// filepath: c:\Projet\MainCourante\MainCourante\MainCourante\config\wallix.php

return [
    'auth_url' => env('WALLIX_AUTH_URL', 'https://wallix.example.com/oauth/authorize'),
    'token_url' => env('WALLIX_TOKEN_URL', 'https://wallix.example.com/oauth/token'),
    'client_id' => env('WALLIX_CLIENT_ID', ''),
    'client_secret' => env('WALLIX_CLIENT_SECRET', ''),
];
