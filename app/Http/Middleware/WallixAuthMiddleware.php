<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class WallixAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur a un token Wallix valide
        if (!Session::has('wallix_token')) {
            return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter']);
        }

        $tokenData = Session::get('wallix_token');
        $expiresAt = Carbon::parse($tokenData['expires_at']);

        // Si le token est expiré
        if ($expiresAt->isPast()) {
            Session::forget('wallix_token');
            return redirect()->route('login')->withErrors(['error' => 'Session expirée, veuillez vous reconnecter']);
        }

        // Si l'utilisateur est connecté mais qu'il manque ses informations
        if (!Session::has('user')) {
            return redirect()->route('login')->withErrors(['error' => 'Informations utilisateur manquantes']);
        }

        return $next($request);
    }
}
