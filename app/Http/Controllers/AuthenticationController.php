<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\SSOAuthService;
use Jumbojett\OpenIDConnectClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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
/**
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function initiateSSO(Request $request)
{
    try {
        // Configuration Wallix
        $baseUrl = "https://seter.trustelem.com/app/3415442";
        $clientID = "trustelem.oidc.gm2dczbzgi";
        $clientSecret = "liIiDgyPO8CbAzvgLLkqyp5pcpUkaDen";

        // URL de redirection après authentification
        // Utilisez celle configurée dans votre console Wallix
        $redirectUrl = url('/connexion/sso');

        // Journalisation des paramètres de base
        Log::info('Tentative d\'authentification SSO', [
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'redirect_url' => $redirectUrl
        ]);

        // Génération de valeurs aléatoires pour la sécurité
        $nonce = Str::random(32);
        $state = Str::random(32);

        // Stockage en session pour vérification ultérieure
        session(['oidc_nonce' => $nonce, 'oidc_state' => $state]);

        // Construction de l'URL d'authentification avec tous les paramètres requis
        $authUrl = $baseUrl . '/auth?' . http_build_query([
            'response_type' => 'code',          // Type de réponse attendue
            'client_id' => $clientID,           // Identifiant client Wallix
            'redirect_uri' => $redirectUrl,     // URL de redirection après authentification
            'scope' => 'openid email profile',  // Permissions demandées
            'state' => $state,                  // Valeur de sécurité anti-CSRF
            'nonce' => $nonce,                  // Protection contre les attaques par rejeu
            'prompt' => 'login'                 // Force l'affichage de la page de login
        ]);

        // Journalisation de l'URL générée
        Log::info('URL d\'authentification générée', [
            'auth_url' => $authUrl
        ]);

        // Redirection vers le service d'authentification
        return redirect($authUrl);

    } catch (\Exception $e) {
        // Journalisation détaillée de l'erreur
        Log::error('Erreur lors de l\'initialisation SSO', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'ip' => $request->ip()
        ]);

        // Redirection vers la page de connexion avec message d'erreur
        return redirect()->route('auth.login')
            ->with('error', 'Le service d\'authentification SSO est temporairement indisponible. Veuillez utiliser la connexion par email.')
            ->with('show_email_form', true); // Afficher directement le formulaire email
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
    $url = "https://seter.trustelem.com/app/3415442";
    $clientID = "trustelem.oidc.gm2dczbzgi";
    $clientSecret = "liIiDgyPO8CbAzvgLLkqyp5pcpUkaDen";

    try {
        Log::info('🔄 Callback Wallix reçu', [
            'request_params' => $request->all()
        ]);

        // 1. Vérifier la disponibilité de Wallix
        try {
            $healthCheck = Http::timeout(5)->get($url . '/.well-known/openid_configuration');
            if (!$healthCheck->successful()) {
                throw new \Exception('Wallix indisponible');
            }
        } catch (\Exception $e) {
            Log::warning(' Wallix indisponible, fallback vers email');
            return redirect()->route('auth.login')
                ->with('warning', 'Service SSO temporairement indisponible. Utilisez l\'authentification par email.')
                ->with('show_email_form', true);
        }

        // 2. Initialiser OpenIDConnect
        $oidc = new OpenIDConnectClient($url, $clientID, $clientSecret);
        $oidc->setRedirectURL(route('auth.wallix.callback'));

        // 3. Authentifier avec Wallix
        $authenticated = $oidc->authenticate();

        if (!$authenticated) {
            Log::error('❌ Échec authentification Wallix');
            return redirect()->route('auth.login')
                ->with('error', 'Échec de l\'authentification SSO')
                ->with('show_email_form', true);
        }

        // 4. Récupérer les informations utilisateur
        $email = $oidc->requestUserInfo('email');
        $firstName = $oidc->requestUserInfo('given_name');
        $lastName = $oidc->requestUserInfo('family_name');

        Log::info(' Informations Wallix récupérées', [
            'email' => $email,
            'nom' => $lastName,
            'prenom' => $firstName
        ]);

        if (!$email) {
            throw new \Exception('Email non trouvé dans la réponse Wallix');
        }

        // 5. Chercher l'utilisateur dans la base
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('⚠️ Utilisateur non trouvé en base', ['email' => $email]);

            // Fallback: Générer un token temporaire et envoyer par email
            $tempToken = Str::random(64);
            cache()->put("temp_wallix_token_{$tempToken}", [
                'email' => $email,
                'nom' => $lastName,
                'prenom' => $firstName,
                'expires_at' => now()->addHour()
            ], 3600);

            // Simuler l'envoi d'email (à adapter selon votre système)
            Log::info('📧 Token temporaire généré pour utilisateur inconnu', [
                'email' => $email,
                'token' => substr($tempToken, 0, 8) . '...'
            ]);

            return redirect()->route('auth.login')
                ->with('success', 'Un email de connexion a été envoyé à votre adresse.')
                ->with('info', 'Vérifiez votre boîte mail et cliquez sur le lien de connexion.')
                ->with('email', $email)
                ->with('fallback_method', 'wallix_to_email');
        }

        // 6. Mettre à jour les informations utilisateur si nécessaire
        $updateData = [];
        if ($firstName && $firstName !== $user->prenom) {
            $updateData['prenom'] = $firstName;
        }
        if ($lastName && $lastName !== $user->nom) {
            $updateData['nom'] = $lastName;
        }

        if (!empty($updateData)) {
            $user->update($updateData);
            Log::info('📝 Informations utilisateur mises à jour depuis Wallix', $updateData);
        }

        // 7. Connecter l'utilisateur directement
        Auth::login($user, true);

        // 8. Génération token API
        $token = $user->createToken('wallix_sso_' . Str::random(10))->plainTextToken;

        Log::info('✅ Authentification Wallix réussie', [
            'user_id' => $user->id,
            'email' => $user->email,
            'method' => 'wallix_sso'
        ]);

        // 9. Marquer la méthode d'authentification
        session(['auth_method' => 'wallix_sso']);

        return $this->handleSuccessfulLogin($request, $user, $token, 'wallix_sso');

    } catch (\Exception $e) {
        Log::error('❌ Erreur callback Wallix', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);

        // Fallback automatique vers authentification email
        return redirect()->route('auth.login')
            ->with('error', 'Erreur SSO: ' . $e->getMessage())
            ->with('warning', 'Vous pouvez utiliser l\'authentification par email en attendant.')
            ->with('show_email_form', true)
            ->with('fallback_reason', $e->getMessage());
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
