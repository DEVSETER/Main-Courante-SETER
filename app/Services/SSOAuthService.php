<?php
// filepath: c:\Projet\MainCourante\MainCourante\MainCourante\app\Services\SSOAuthService.php

namespace App\Services;

use Schema;
use Carbon\Carbon;
use App\Models\User;
use App\Models\EmailToken;
use Jumbojett\OpenIDConnectClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SSOAuthService
{
    private $config;

    public function __construct()
    {
        $this->config = config('oidc.wallix');
    }

    /**
     * Tenter l'authentification SSO avec Wallix
     */
    public function attemptWallixAuth(): array
    {
        try {
            // Pour les tests, on simule que Wallix est indisponible
            // En production, décommentez le code ci-dessous

            /*
            $oidc = new OpenIDConnectClient(
                $this->config['issuer'],
                $this->config['client_id'],
                $this->config['client_secret']
            );

            // Configuration du timeout
            $oidc->setTimeout($this->config['timeout']);

            // Configuration des scopes
            foreach ($this->config['scopes'] as $scope) {
                $oidc->addScope($scope);
            }

            // Configuration de l'URL de redirection
            $oidc->setRedirectURL($this->config['redirect_uri']);

            // Test de connectivité
            if (!$this->isWallixAvailable()) {
                throw new \Exception('Wallix server unavailable');
            }

            // Authentification
            $oidc->authenticate();

            // Récupération des informations utilisateur
            $userInfo = $oidc->requestUserInfo();

            Log::info('Authentification Wallix réussie', [
                'user_email' => $userInfo->email ?? 'unknown',
                'user_name' => $userInfo->name ?? 'unknown'
            ]);

            return [
                'success' => true,
                'method' => 'wallix_sso',
                'user_info' => $userInfo
            ];
            */

            // Simulation d'échec pour forcer le fallback email
            throw new \Exception('Wallix indisponible (mode test)');

        } catch (\Exception $e) {
            Log::warning('Échec authentification Wallix', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Tester la disponibilité de Wallix
     */
    private function isWallixAvailable(): bool
    {
        try {
            // En mode test, on retourne false
            return false;

            /*
            $context = stream_context_create([
                'http' => [
                    'timeout' => $this->config['timeout'] ?? 10
                ]
            ]);

            $headers = @get_headers($this->config['issuer'], false, $context);
            return $headers !== false;
            */

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Générer et envoyer un token par email
     */
    public function sendEmailToken(string $email): array
{
    try {
        Log::info('=== DÉBUT ENVOI EMAIL TOKEN ===');
        Log::info('Email destinataire:', ['email' => $email]);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::warning('Utilisateur non trouvé:', ['email' => $email]);
            return [
                'success' => false,
                'error' => 'Utilisateur non trouvé pour cet email'
            ];
        }

        Log::info('Utilisateur trouvé:', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);

        // Supprimer les anciens tokens non utilisés
        $deletedCount = EmailToken::where('email', $email)
            ->where('used', false)
            ->delete();
        Log::info('Anciens tokens supprimés:', ['count' => $deletedCount]);

        // ✅ CORRECTION : Convertir en entier et valeur par défaut
        $token = EmailToken::generateToken();
        $expiry = (int) config('oidc.email_token.expiry', 3600); // Force la conversion en int
        $expiresAt = Carbon::now()->addSeconds($expiry); // Maintenant ça marche

        Log::info('Token généré:', [
            'token_preview' => substr($token, 0, 8) . '...',
            'expires_at' => $expiresAt->toDateTimeString(),
            'expiry_seconds' => $expiry
        ]);

        // Sauvegarder le token en base
        $emailToken = EmailToken::create([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt
        ]);
        Log::info('Token sauvegardé en base:', ['token_id' => $emailToken->id]);

        // Préparer l'URL de connexion
        $loginUrl = route('auth.email.verify', ['token' => $token]);
        Log::info('URL de connexion générée:', ['url' => $loginUrl]);

        // Envoyer l'email
        Log::info('=== DÉBUT ENVOI EMAIL ===');

        Mail::send('emails.auth-token', [
            'user' => $user,
            'token' => $token,
            'expires_at' => $expiresAt,
            'login_url' => $loginUrl
        ], function ($message) use ($email, $user) {
            $message->to($email)
                ->subject('Connexion Main Courante - Token d\'authentification');

            Log::info('Email préparé:', [
                'to' => $email,
                'subject' => 'Connexion Main Courante - Token d\'authentification'
            ]);
        });

        Log::info('✅ EMAIL ENVOYÉ AVEC SUCCÈS');

        return [
            'success' => true,
            'method' => 'email_token',
            'expires_at' => $expiresAt
        ];

    } catch (\Exception $e) {
        Log::error('❌ ERREUR GLOBALE ENVOI TOKEN EMAIL', [
            'email' => $email,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
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

            Log::info('Authentification par token email réussie', [
                'email' => $emailToken->email,
                'user_id' => $user->id
            ]);

            return [
                'success' => true,
                'method' => 'email_token',
                'user' => $user
            ];

        } catch (\Exception $e) {
            Log::error('Erreur vérification token email', [
                'token' => substr($token, 0, 8) . '...',
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Erreur lors de la vérification du token'
            ];
        }
    }

    /**
     * Trouver ou créer un utilisateur depuis les infos SSO
     */
    public function findOrCreateSSOUser($userInfo): User
    {
        $email = $userInfo->email ?? null;
        $name = $userInfo->name ?? $userInfo->preferred_username ?? 'Utilisateur SSO';

        if (!$email) {
            throw new \Exception('Email non fourni par le provider SSO');
        }

        // Recherche par email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Mise à jour des informations SSO
            $user->update([
                'name' => $name,
                'sso_provider' => 'wallix',
                'sso_id' => $userInfo->sub ?? null,
                'last_login_at' => now(),
                'last_login_method' => 'wallix_sso'
            ]);
            return $user;
        }

        // Création d'un nouvel utilisateur
        return User::create([
            'name' => $name,
            'email' => $email,
            'sso_provider' => 'wallix',
            'sso_id' => $userInfo->sub ?? null,
            'email_verified_at' => now(),
            'last_login_at' => now(),
            'last_login_method' => 'wallix_sso'
        ]);
    }
}
