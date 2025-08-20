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
use Illuminate\Support\Facades\Session;

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
     * Login with token form
     */
    public function loginForm(){
        return view('auth.login');
    }

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    // Recherche de l'utilisateur actif
    $user = User::where('email', $request->email)
        // ->where('status', true)
        ->first();

    // Vérification des droits d'accès
    if ($user && $user->entite && in_array($user->entite->code, ['SR COF', 'PTP', 'CIV', 'HC', 'CM'])) {
        $token = Str::random(20);

        // Stockage sécurisé en session
        session(['token' => $token, 'userTryingToConnect' => $user]);

        try {
            $emailResult = $this->ssoService->sendEmailToken($user->email);

            if (!$emailResult['success']) {
                return redirect()->route('auth.loginForm')
                    ->with('error', $emailResult['error'] ?? "Erreur lors de l'envoi du mail.");
            }

            return redirect()->route('auth.email.verify');
        } catch (\Exception $exception) {
            Log::error('Erreur envoi mail token', [
                'error' => $exception->getMessage(),
                'email' => $user->email
            ]);
            $message = "Erreur lors de l'envoi du mail.";
            return view('error-404', compact('message'));
        }
    } else {
        return redirect()->route('auth.loginForm')
            ->with('error', "Vous n'avez pas accès à cette plateforme");
    }
}

    public function initiateSSO(){

        $url = "https://seter.trustelem.com/app/3415442";
         $clientID = "trustelem.oidc.gm2dczbzgi";
         $clientSecret = "liIiDgyPO8CbAzvgLLkqyp5pcpUkaDen";


         $oidc = new OpenIDConnectClient($url, $clientID, $clientSecret);
         $oidc->setVerifyPeer(false); // à utiliser uniquement pour tester en local

        $oidc->authenticate();

        $email = $oidc->requestUserInfo('email');

        $User = User::where(['email' => $email])->first();
        if ($User != null && ($User->entite->code == 'SR COF' || $User->entite->code == 'PTP'|| $User->entite->code == 'CIV'|| $User->entite->code == 'HC'
                || $User->entite->code == 'CM' )){
            $user = User::where(['id' => $User->id])->first();
            if ($user != null){
                Auth::login($user);

            }

            return redirect()->route('evenements.index');

        }else {
            return redirect()->route('auth.loginSSOForm')->with('error', "Vous n'avez pas accès à cette plateforme");
        }

    }

//     /**
//      * Initie l'authentification SSO
//      */
// /**
//  * @param Request $request
//  * @return \Illuminate\Http\RedirectResponse
//  */
// public function initiateSSO(Request $request)
// {
//     try {
//         // Configuration Wallix
//         $baseUrl = "https://seter.trustelem.com/app/3415442";
//         $clientID = "trustelem.oidc.gm2dczbzgi";
//         $clientSecret = "liIiDgyPO8CbAzvgLLkqyp5pcpUkaDen";

//         // URL de redirection après authentification
//         // Utilisez celle configurée dans votre console Wallix
//           $redirectUrl = url(path: '/connexion/sso/callback');
//         // Journalisation des paramètres de base
//         Log::info('Tentative d\'authentification SSO', [
//             'ip' => $request->ip(),
//             'user_agent' => $request->header('User-Agent'),
//             'redirect_url' => $redirectUrl
//         ]);

//         // Génération de valeurs aléatoires pour la sécurité
//         $nonce = Str::random(32);
//         $state = Str::random(32);

//         // Stockage en session pour vérification ultérieure
//         session(['oidc_nonce' => $nonce, 'oidc_state' => $state]);
//             session()->save();


//         // Construction de l'URL d'authentification avec tous les paramètres requis
//         $authUrl = $baseUrl . '/auth?' . http_build_query([
//             'response_type' => 'code',          // Type de réponse attendue
//             'client_id' => $clientID,           // Identifiant client Wallix
//             'redirect_uri' => $redirectUrl,     // URL de redirection après authentification
//             'scope' => 'openid email profile',  // Permissions demandées
//             'state' => $state,                  // Valeur de sécurité anti-CSRF
//             'nonce' => $nonce,                  // Protection contre les attaques par rejeu
//             'prompt' => 'login'                 // Force l'affichage de la page de login
//         ]);

//         // Journalisation de l'URL générée
//         Log::info('URL d\'authentification générée', [
//             'auth_url' => $authUrl
//         ]);

//         // Redirection vers le service d'authentification
//         return redirect($authUrl);

//     } catch (\Exception $e) {
//         // Journalisation détaillée de l'erreur
//         Log::error('Erreur lors de l\'initialisation SSO', [
//             'message' => $e->getMessage(),
//             'code' => $e->getCode(),
//             'file' => $e->getFile(),
//             'line' => $e->getLine(),
//             'trace' => $e->getTraceAsString(),
//             'ip' => $request->ip()
//         ]);

//         // Redirection vers la page de connexion avec message d'erreur
//         return redirect()->route('auth.login')
//             ->with('error', 'Le service d\'authentification SSO est temporairement indisponible. Veuillez utiliser la connexion par email.')
//             ->with('show_email_form', true); // Afficher directement le formulaire email
//     }
// }

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


    //Première méthode //
    /**
     * Gère le callback Wallix SSO
     */
    /**
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */


//     public function handleWallixCallback(Request $request)
// {
//     try {
//         // Journalisation de la requête de callback
//         Log::info('Callback SSO reçu', [
//             'params' => $request->all(),
//             'has_code' => $request->has('code'),
//             'has_state' => $request->has('state')
//         ]);

//         // Vérifier la présence du code d'autorisation
//         if (!$request->has('code')) {
//             Log::warning('Callback sans code d\'autorisation', [
//                 'params' => $request->all()
//             ]);
//             throw new \Exception('Code d\'autorisation manquant');
//         }

//         // Vérifier le state pour prévenir les attaques CSRF
//         if (!$request->has('state') || $request->state !== session('oidc_state')) {
//             Log::warning('Validation du state échouée', [
//                 'received' => $request->state ?? 'null',
//                 'expected' => session('oidc_state') ?? 'null'
//             ]);
//             throw new \Exception('État de session invalide');
//         }

//         // Configuration Wallix
//         $baseUrl = "https://seter.trustelem.com/app/3415442";
//         $clientID = "trustelem.oidc.gm2dczbzgi";
//         $clientSecret = "liIiDgyPO8CbAzvgLLkqyp5pcpUkaDen";
//             $redirectUrl = url('/connexion/sso/callback');
//         // Échange du code contre un token
//         $response = Http::asForm()->post($baseUrl . '/token', [
//             'grant_type' => 'authorization_code',
//             'code' => $request->code,
//             'redirect_uri' => $redirectUrl,
//             'client_id' => $clientID,
//             'client_secret' => $clientSecret
//         ]);

//         if (!$response->successful()) {
//             Log::error('Échec échange code contre token', [
//                 'status' => $response->status(),
//                 'body' => $response->body()
//             ]);
//             throw new \Exception('Échec de récupération du token: ' . $response->status());
//         }

//         $tokenData = $response->json();

//         // Vérifier la présence de l'ID token
//         if (!isset($tokenData['id_token'])) {
//             Log::error('ID token manquant dans la réponse', [
//                 'response' => $tokenData
//             ]);
//             throw new \Exception('ID token manquant dans la réponse');
//         }

//         // Récupérer les informations utilisateur
//         $idToken = $tokenData['id_token'];
//         $parts = explode('.', $idToken);
//         $payload = json_decode(base64_decode($parts[1]), true);

//         $email = $payload['email'] ?? null;

//         if (!$email) {
//             Log::error('Email manquant dans l\'ID token', [
//                 'payload' => $payload
//             ]);
//             throw new \Exception('Email non fourni');
//         }

//         Log::info('Utilisateur authentifié via SSO', ['email' => $email]);

//         // Recherche de l'utilisateur dans la base de données
//         $user = User::where('email', $email)->first();

//         if (!$user) {
//             Log::warning('Tentative de connexion avec un email non enregistré', [
//                 'email' => $email
//             ]);
//             return redirect()->route('auth.login')
//                 ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette application');
//         }

//         // Connecter l'utilisateur
//         Auth::login($user);

//         // Nettoyer les données de session
//         session()->forget(['oidc_state', 'oidc_nonce']);

//         Log::info('Connexion SSO réussie', [
//             'user_id' => $user->id,
//             'email' => $user->email
//         ]);

//         // Rediriger vers la page d'accueil
//         return redirect()->route('evenements.index')
//             ->with('success', 'Connexion réussie');

//     } catch (\Exception $e) {
//         // Journalisation détaillée de l'erreur
//         Log::error('Erreur lors du traitement du callback SSO', [
//             'message' => $e->getMessage(),
//             'code' => $e->getCode(),
//             'file' => $e->getFile(),
//             'line' => $e->getLine(),
//             'trace' => $e->getTraceAsString()
//         ]);

//         // Nettoyer les données de session
//         session()->forget(['oidc_state', 'oidc_nonce']);

//         // Rediriger vers la page de connexion avec message d'erreur
//         return redirect()->route('auth.login')
//             ->with('error', 'Erreur lors de l\'authentification SSO: ' . $e->getMessage())
//             ->with('show_email_form', true);
//     }
// }

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

    // /**
    //  * Renvoie un nouveau token par email
    //  */
    // public function resendEmailToken(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|email|exists:users,email'
    //         ]);

    //         $email = $request->input('email');
    //         Log::info('🔄 Renvoi token email', ['email' => $email]);

    //         // Suppression des anciens tokens
    //         EmailToken::where('email', $email)
    //                  ->where('created_at', '<', now())
    //                  ->delete();

    //         $result = $this->ssoService->sendEmailToken($email);

    //         return response()->json([
    //             'success' => $result['success'],
    //             'message' => $result['success'] ? 'Nouveau token envoyé' : 'Erreur envoi token',
    //             'expires_at' => $result['expires_at'] ?? null
    //         ], $result['success'] ? 200 : 400);

    //     } catch (\Exception $e) {
    //         Log::error('❌ Erreur renvoi token', [
    //             'error' => $e->getMessage(),
    //             'email' => $request->input('email')
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Erreur lors du renvoi du token'
    //         ], 500);
    //     }
    // }

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
    /**
     * Méthode utilitaire pour gérer un échec de connexion
     */

}
