<?php

namespace App\Http\Controllers;

use App\Services\SSOAuthService;
use App\Models\User;
use App\Models\EmailToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    private $ssoService;

    public function __construct(SSOAuthService $ssoService)
    {
        $this->ssoService = $ssoService;
    }

    /**
     * Affiche la page de connexion
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('evenements.index');
        }
        return view('auth.boxed-signin');
    }

    /**
     * Initie l'authentification SSO
     */
    public function initiateSSO(Request $request)
    {
        try {
            Log::info('🚀 Tentative de connexion SSO');

            $wallixResult = $this->ssoService->attemptWallixAuth();

            if ($wallixResult['success']) {
                return response()->json([
                    'success' => true,
                    'method' => 'wallix_sso',
                    'message' => 'Redirection vers Wallix...'
                ]);
            }

            Log::warning('❌ SSO indisponible', [
                'error' => $wallixResult['error'] ?? 'Erreur inconnue'
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Service SSO temporairement indisponible'
            ], 503);

        } catch (\Exception $e) {
            Log::error('🔥 Erreur critique SSO', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'initialisation SSO'
            ], 500);
        }
    }

    /**
     * Initie l'authentification par email
     */
    public function initiateEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);

            $email = $request->input('email');
            Log::info('📧 Tentative connexion email', ['email' => $email]);

            $emailResult = $this->ssoService->sendEmailToken($email);

            if (!$emailResult['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $emailResult['error']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'method' => 'email_token',
                'message' => 'Lien de connexion envoyé',
                'expires_at' => $emailResult['expires_at']
            ]);

        } catch (\Exception $e) {
            Log::error('🔥 Erreur envoi email', [
                'error' => $e->getMessage(),
                'email' => $request->input('email')
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'envoi du token'
            ], 500);
        }
    }

    /**
     * Gère le callback Wallix SSO
     */
    public function handleWallixCallback(Request $request)
    {
        try {
            Log::info('🔄 Callback Wallix reçu');

            $wallixResult = $this->ssoService->handleWallixCallback($request);

            if (!$wallixResult['success']) {
                throw new \Exception($wallixResult['error']);
            }

            $user = $wallixResult['user'];
            Auth::login($user);

            // Génération token API
            $token = $user->createToken('wallix_sso_' . Str::random(10))->plainTextToken;

            Log::info('✅ Authentification Wallix réussie', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return $this->handleSuccessfulLogin($request, $user, $token, 'wallix_sso');

        } catch (\Exception $e) {
            Log::error('❌ Erreur callback Wallix', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->handleFailedLogin($request, 'Erreur d\'authentification SSO');
        }
    }

    /**
     * Vérifie le token reçu par email
     */
    public function verifyEmailToken(Request $request, string $token)
    {
        try {
            Log::info('🔍 Vérification token email', ['token' => substr($token, 0, 8) . '...']);

            $result = $this->ssoService->verifyEmailToken(
                $token,
                $request->header('User-Agent'),
                $request->ip()
            );

            if (!$result['success']) {
                throw new \Exception($result['error']);
            }

            $user = $result['user'];
            Auth::login($user);

            // Génération token API
            $apiToken = $user->createToken('email_' . Str::random(10))->plainTextToken;

            Log::info('✅ Authentification email réussie', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return $this->handleSuccessfulLogin($request, $user, $apiToken, 'email_token');

        } catch (\Exception $e) {
            Log::error('❌ Erreur vérification token', [
                'error' => $e->getMessage(),
                'token' => substr($token, 0, 8) . '...'
            ]);

            return $this->handleFailedLogin($request, 'Token invalide ou expiré');
        }
    }

    /**
     * Gère la déconnexion
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                Log::info('👋 Déconnexion utilisateur', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);

                // Révocation de tous les tokens
                $user->tokens()->delete();

                // Suppression session
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'Déconnexion réussie'])
            : redirect()->route('auth.login')->with('success', 'Déconnexion réussie');

        } catch (\Exception $e) {
            Log::error('❌ Erreur déconnexion', [
                'error' => $e->getMessage(),
                'user' => $user->email ?? 'unknown'
            ]);

            return $request->expectsJson()
                ? response()->json(['success' => false, 'error' => 'Erreur lors de la déconnexion'], 500)
                : redirect()->route('login')->withErrors(['error' => 'Erreur lors de la déconnexion']);
        }
    }

    /**
     * Renvoie un nouveau token par email
     */
    public function resendEmailToken(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);

            $email = $request->input('email');
            Log::info('🔄 Renvoi token email', ['email' => $email]);

            // Suppression des anciens tokens
            EmailToken::where('email', $email)
                     ->where('created_at', '<', now())
                     ->delete();

            $result = $this->ssoService->sendEmailToken($email);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Nouveau token envoyé' : 'Erreur envoi token',
                'expires_at' => $result['expires_at'] ?? null
            ], $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            Log::error('❌ Erreur renvoi token', [
                'error' => $e->getMessage(),
                'email' => $request->input('email')
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du renvoi du token'
            ], 500);
        }
    }

    /**
     * Méthode utilitaire pour gérer une connexion réussie
     */
    private function handleSuccessfulLogin(Request $request, User $user, string $token, string $method)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'access_token' => $token,
                'user' => $user,
                'auth_method' => $method
            ]);
        }

        return redirect()->route('evenements.index')
            ->with('success', 'Connexion réussie');
    }

    /**
     * Méthode utilitaire pour gérer un échec de connexion
     */
    private function handleFailedLogin(Request $request, string $error)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => $error
            ], 400);
        }

        return redirect()->route('auth.login')
            ->withErrors(['error' => $error]);
    }
}
