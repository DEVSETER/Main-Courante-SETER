<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SSOServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sso.config', function () {
            if (app()->environment('local') && config('sso.mock_mode')) {
                return [
                    'authorization_url' => url('/mock-sso/auth'),
                    'token_url' => url('/mock-sso/token'),
                    'userinfo_url' => url('/mock-sso/userinfo'),
                    'client_id' => 'mock-client-id',
                    'redirect_uri' => url('/connexion/sso/callback'),
                ];
            }

            return [
                'authorization_url' => config('sso.authorization_url'),
                'token_url' => config('sso.token_url'),
                'userinfo_url' => config('sso.userinfo_url'),
                'client_id' => config('sso.client_id'),
                'redirect_uri' => config('sso.redirect_uri'),
            ];
        });
    }
}
