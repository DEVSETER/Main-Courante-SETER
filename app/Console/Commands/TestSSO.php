<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestSSO extends Command
{
    protected $signature = 'sso:test {--mock : Utiliser le serveur mock}';
    protected $description = 'Tester la configuration SSO';

    public function handle()
    {
        $this->info('🧪 Test de la configuration SSO...');

        // CORRECTION: Utiliser l'URL complète avec port
        $baseUrl = 'http://localhost:8000';

        $this->info("Base URL: $baseUrl");

        if ($this->option('mock')) {
            $this->testMockSSO($baseUrl);
        } else {
            $this->testRealSSO();
        }
    }

    private function testMockSSO($baseUrl)
    {
        $this->info('🎭 Testing Mock SSO...');

        // Test 1: Health check
        try {
            $this->info('Testing Laravel server...');
            $healthResponse = Http::timeout(5)->get("$baseUrl/health-check");
            if ($healthResponse->successful()) {
                $this->info('✅ Laravel server: OK');
                $data = $healthResponse->json();
                $this->line('Environment: ' . ($data['environment'] ?? 'unknown'));
            } else {
                $this->error('❌ Laravel server not responding');
                return;
            }
        } catch (\Exception $e) {
            $this->error('❌ Cannot reach Laravel server: ' . $e->getMessage());
            $this->warn('💡 Make sure Laravel is running: php artisan serve');
            return;
        }

        // Test 2: Auth endpoint
        try {
            $authUrl = "$baseUrl/mock-sso/auth?client_id=test&redirect_uri=$baseUrl/test&state=test123";
            $this->line("Testing Auth URL: $authUrl");

            $authResponse = Http::timeout(5)->get($authUrl);
            if ($authResponse->successful()) {
                $this->info('✅ Auth endpoint: OK');
            } else {
                $this->error('❌ Auth endpoint failed: ' . $authResponse->status());
                $this->line('Response: ' . $authResponse->body());
            }
        } catch (\Exception $e) {
            $this->error('❌ Auth endpoint error: ' . $e->getMessage());
        }

        // Test 3: Token endpoint
        try {
            $this->info('Testing Token endpoint...');
            $tokenResponse = Http::timeout(5)
                ->asForm()
                ->post("$baseUrl/mock-sso/token", [
                    'code' => 'mock_auth_code_test123',
                    'grant_type' => 'authorization_code',
                    'client_id' => 'test',
                    'redirect_uri' => "$baseUrl/test"
                ]);

            if ($tokenResponse->successful()) {
                $this->info('✅ Token endpoint: OK');
                $data = $tokenResponse->json();
                $this->line('Access token: ' . substr($data['access_token'] ?? 'N/A', 0, 20) . '...');
            } else {
                $this->error('❌ Token endpoint failed: ' . $tokenResponse->status());
                $this->line('Response: ' . $tokenResponse->body());
            }
        } catch (\Exception $e) {
            $this->error('❌ Token endpoint error: ' . $e->getMessage());
        }
    }

    private function testRealSSO()
    {
        $this->info('🔗 Testing Real SSO configuration...');

        $authUrl = config('sso.authorization_url');
        $tokenUrl = config('sso.token_url');

        $this->line("Auth URL: $authUrl");
        $this->line("Token URL: $tokenUrl");

        if (!$authUrl || !$tokenUrl) {
            $this->error('❌ SSO configuration missing. Check your .env file');
            return;
        }

        try {
            $this->info('Testing connectivity to SSO server...');
            $response = Http::timeout(10)->get($authUrl);
            $this->info('✅ SSO Server accessible (Status: ' . $response->status() . ')');
        } catch (\Exception $e) {
            $this->error('❌ SSO Server not accessible: ' . $e->getMessage());
        }
    }
}
