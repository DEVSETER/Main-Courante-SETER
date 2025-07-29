<?php
// app/Http/Controllers/AuthenticationController.php

namespace App\Http\Controllers;

use App\Services\SSOAuthService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    private $ssoService;

    public function __construct(SSOAuthService $ssoService)
    {
        $this->ssoService = $ssoService;
    }

    /**
     * Afficher la page de connexion
     */
    public function showLogin()
    {
        return view('auth.boxed-signin');
    }

    /**
     * Initier l'authentification (SSO ou email)
     */
    public function initiateAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');

        Log::info('Initiation authentification', ['email' => $email]);

        // 1. Tentative d'authentification SSO Wallix
        $wallixResult = $this->ssoService->attemptWallixAuth();

        if ($wallixResult['success']) {
            // SSO réussi - rediriger vers Wallix
            return response()->json([
                'success' => true,
                'method' => 'wallix_sso',
                'message' => 'Redirection vers Wallix...'
            ]);
        }

        // 2. Wallix indisponible - envoi du token par email
        Log::info('Wallix indisponible, envoi token email', [
            'email' => $email,
            'wallix_error' => $wallixResult['error']
        ]);

        $emailResult = $this->ssoService->sendEmailToken($email);

        if ($emailResult['success']) {
            return response()->json([
                'success' => true,
                'method' => 'email_token',
                'message' => 'Un lien de connexion a été envoyé à votre adresse email',
                'expires_at' => $emailResult['expires_at']
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $emailResult['error']
        ], 400);
    }

    /**
     * Callback Wallix SSO
     */
    public function wallixCallback(Request $request)
    {
        try {
            $wallixResult = $this->ssoService->attemptWallixAuth();

            if (!$wallixResult['success']) {
                return redirect()->route('login')
                    ->withErrors(['error' => 'Authentification SSO échouée']);
            }

            // Créer ou récupérer l'utilisateur
            $user = $this->ssoService->findOrCreateSSOUser($wallixResult['user_info']);

            // Authentifier l'utilisateur
            Auth::login($user);

            // Générer le token API
            $token = $user->createToken('wallixSSOToken')->plainTextToken;

            Log::info('Authentification Wallix réussie', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Redirection ou réponse JSON selon le contexte
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'access_token' => $token,
                    'user' => $user,
                    'auth_method' => 'wallix_sso'
                ]);
            }

            return redirect()->route('evenements.index')
                ->with('success', 'Connexion réussie via Wallix');

        } catch (\Exception $e) {
            Log::error('Erreur callback Wallix', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->withErrors(['error' => 'Erreur lors de l\'authentification ']);
        }
    }

    /**
     * Vérification du token email
     */
    public function verifyEmailToken(Request $request, string $token)
    {
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();

        $result = $this->ssoService->verifyEmailToken($token, $userAgent, $ipAddress);

        if (!$result['success']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 400);
            }

            return redirect()->route('login')
                ->withErrors(['error' => $result['error']]);
        }

        // Authentifier l'utilisateur
        Auth::login($result['user']);

        // Générer le token API
        $apiToken = $result['user']->createToken('emailToken')->plainTextToken;

        Log::info('Authentification par token email réussie', [
            'user_id' => $result['user']->id,
            'email' => $result['user']->email
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'access_token' => $apiToken,
                'user' => $result['user'],
                'auth_method' => 'email_token'
            ]);
        }

        return redirect()->route('evenements.index')
            ->with('success', 'Connexion réussie via token email');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Supprimer tous les tokens de l'utilisateur
            $user->tokens()->delete();

            Log::info('Déconnexion utilisateur', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        }

        Auth::logout();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie'
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Déconnexion réussie');
    }

    /**
     * Resend email token (si premier envoi a échoué)
     */
    public function resendEmailToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $result = $this->ssoService->sendEmailToken($request->input('email'));

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
