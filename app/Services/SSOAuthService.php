<?php
// filepath: c:\Projet\MainCourante\app\Services\SSOAuthService.php

namespace App\Services;

use Schema;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\EmailToken;
use Jumbojett\OpenIDConnectClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class SSOAuthService
{
    private $config;

    public function __construct()
    {
        // appel de wallix.php
        $this->config = config('wallix');
    }

    /**
     * Tenter l'authentification SSO avec Wallix
     * Redirige vers Wallix pour récupérer l'email
     */
    public function attemptWallixAuth(): array
    {
        try {
            Log::info('🚀 Tentative authentification Wallix');

            // Construction de l'URL d'autorisation Wallix
            $authUrl = $this->config['oidc_issuer'] . '/auth?' . http_build_query([
                'client_id' => $this->config['client_id'],
                'redirect_uri' => $this->config['redirect_uri'],
                'response_type' => 'code',
                'scope' => 'openid email profile',
                'state' => csrf_token() // Protection CSRF
            ]);

            Log::info('🔗 URL d\'autorisation générée', ['url' => $authUrl]);

            return [
                'success' => true,
                'auth_url' => $authUrl,
                'method' => 'wallix_redirect'
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur génération URL Wallix', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Gère le callback Wallix - Récupère l'email et envoie un token
     */
    public function handleWallixCallback(Request $request): array
    {
        try {
            Log::info('🔄 Traitement callback Wallix');

            // Vérifier la présence du code d'autorisation
            if (!$request->has('code')) {
                throw new Exception('Code d\'autorisation manquant');
            }

            if ($request->has('error')) {
                throw new Exception('Erreur SSO: ' . $request->get('error_description', $request->get('error')));
            }

            $code = $request->get('code');
            Log::info('✅ Code d\'autorisation reçu');

            // Échanger le code contre un token d'accès
            $tokenResponse = Http::timeout($this->config['timeout'])->asForm()->post($this->config['oidc_issuer'] . '/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->config['redirect_uri'],
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
            ]);

            if (!$tokenResponse->successful()) {
                throw new Exception('Échec obtention token: ' . $tokenResponse->body());
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                throw new Exception('Token d\'accès manquant');
            }

            Log::info('✅ Token d\'accès obtenu');

            // Récupérer les informations utilisateur (principalement l'email)
            $userResponse = Http::timeout($this->config['timeout'])
                ->withToken($accessToken)
                ->get($this->config['oidc_issuer'] . '/userinfo');

            if (!$userResponse->successful()) {
                throw new Exception('Échec récupération utilisateur: ' . $userResponse->body());
            }

            $userInfo = $userResponse->json();
            $email = $userInfo['email'] ?? null;

            if (!$email) {
                throw new Exception('Email manquant dans les informations utilisateur');
            }

            Log::info('✅ Email récupéré depuis Wallix', ['email' => $email]);

            // Vérifier que l'utilisateur existe dans notre base
            $user = User::where('email', $email)->first();
            if (!$user) {
                throw new Exception('Utilisateur non trouvé pour l\'email: ' . $email);
            }

            // MAINTENANT : Envoyer un token par email comme pour l'auth classique
            $tokenResult = $this->sendEmailToken($email);

            if (!$tokenResult['success']) {
                throw new Exception('Impossible d\'envoyer le token: ' . $tokenResult['error']);
            }

            Log::info('✅ Token email envoyé après authentification Wallix');

            return [
                'success' => true,
                'method' => 'wallix_to_email',
                'email' => $email,
                'message' => 'Email de connexion envoyé',
                'expires_at' => $tokenResult['expires_at']
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur callback Wallix', [
                'error' => $e->getMessage(),
                'request_params' => $request->all()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Générer et envoyer un token par email
     */
    public function sendEmailToken(string $email): array
    {
        try {
            Log::info('📧 Envoi token email', ['email' => $email]);

            // Vérifier si l'utilisateur existe
            $user = User::where('email', $email)->first();
            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'Utilisateur non trouvé pour cet email'
                ];
            }

            // Supprimer les anciens tokens non utilisés
            EmailToken::where('email', $email)->where('used', false)->delete();

            // Générer un nouveau token
            $token = EmailToken::generateToken();
            $expiry = (int) config('app.email_token_expiry', 3600); // 1 heure par défaut
            $expiresAt = Carbon::now()->addSeconds($expiry);

            // Sauvegarder le token
            EmailToken::create([
                'email' => $email,
                'token' => $token,
                'expires_at' => $expiresAt
            ]);

            // Préparer l'URL de connexion
            $loginUrl = route('auth.email.verify', ['token' => $token]);

            // Envoyer l'email
            Mail::send('emails.auth-token', [
                'user' => $user,
                'token' => $token,
                'expires_at' => $expiresAt,
                'login_url' => $loginUrl
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Connexion Main Courante - Token d\'authentification');
            });

            Log::info('✅ Token email envoyé avec succès');

            return [
                'success' => true,
                'method' => 'email_token',
                'expires_at' => $expiresAt
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur envoi token email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier un token email
     */
    public function verifyEmailToken(string $token, string $userAgent = null, string $ipAddress = null): array
    {
        try {
            $emailToken = EmailToken::where('token', $token)->first();

            if (!$emailToken) {
                return [
                    'success' => false,
                    'error' => 'Token invalide'
                ];
            }

            if (!$emailToken->isValid()) {
                return [
                    'success' => false,
                    'error' => 'Token expiré ou déjà utilisé'
                ];
            }

            // Marquer le token comme utilisé
            $emailToken->markAsUsed($userAgent, $ipAddress);

            // Récupérer l'utilisateur
            $user = User::where('email', $emailToken->email)->first();

            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'Utilisateur non trouvé'
                ];
            }

            Log::info('✅ Authentification par token réussie', [
                'email' => $emailToken->email,
                'user_id' => $user->id
            ]);

            return [
                'success' => true,
                'method' => 'email_token',
                'user' => $user
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur vérification token', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Erreur lors de la vérification du token'
            ];
        }
    }
}