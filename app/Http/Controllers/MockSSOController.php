<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MockSSOController extends Controller
{
    public function authorize(Request $request)
    {
        // Simuler la page de connexion Wallix
        $clientId = $request->get('client_id');
        $redirectUri = $request->get('redirect_uri');
        $state = $request->get('state');
        $scope = $request->get('scope');

        // En mode test, on peut soit :
        // 1. Retourner directement un code
        $authCode = 'mock_auth_code_' . Str::random(32);

        // 2. Ou afficher une page de test
        return view('mock.sso-login', compact('clientId', 'redirectUri', 'state', 'authCode'));
    }

    public function token(Request $request)
    {
        // Simuler l'échange code -> token
        $code = $request->get('code');

        if (str_starts_with($code, 'mock_auth_code_')) {
            return response()->json([
                'access_token' => 'mock_access_token_' . Str::random(64),
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'id_token' => $this->generateMockJWT(),
                'scope' => 'openid email profile'
            ]);
        }

        return response()->json(['error' => 'invalid_grant'], 400);
    }

    public function userinfo(Request $request)
    {
        // Simuler les informations utilisateur
        $token = $request->bearerToken();

        if (str_starts_with($token, 'mock_access_token_')) {
            return response()->json([
                'sub' => 'user123',
                'email' => 'test.user@company.com',
                'name' => 'Test User',
                'given_name' => 'Test',
                'family_name' => 'User',
                'preferred_username' => 'testuser',
                'groups' => ['admin', 'users']
            ]);
        }

        return response()->json(['error' => 'invalid_token'], 401);
    }

    private function generateMockJWT()
    {
        // JWT factice pour les tests
        $header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = base64_encode(json_encode([
            'sub' => 'user123',
            'email' => 'test.user@company.com',
            'name' => 'Test User',
            'iat' => time(),
            'exp' => time() + 3600
        ]));
        $signature = base64_encode('mock_signature');

        return "$header.$payload.$signature";
    }
}
