<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use PDOException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class DatabaseAvailabilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {


    $excludedRoutes = [
            'admin.system.status',
            'database.unavailable'
        ];

        if (in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }

        try {
            // Test rapide de connexion
            DB::connection()->getPdo();

        } catch (QueryException $e) {
            return $this->handleDatabaseError($request, $e);
        } catch (PDOException $e) {
            return $this->handleDatabaseError($request, $e);
        } catch (\Exception $e) {
            if ($this->isDatabaseConnectionError($e)) {
                return $this->handleDatabaseError($request, $e);
            }
        }

        return $next($request);
    }


    /**
     * Vérifier si l'erreur est liée à la connexion DB
     */
    private function isDatabaseConnectionError($exception)
    {
        $message = strtolower($exception->getMessage());

        $connectionErrors = [
            'connection refused',
            'connexion n\'a pu être établie',
            'aucune connexion',
            'connection timeout',
            'connection failed',
            'server has gone away',
            'lost connection',
            'access denied',
            'unknown database',
            'can\'t connect to mysql',
            'sqlstate[hy000] [2002]',
            'sqlstate[08006]'
        ];

        foreach ($connectionErrors as $error) {
            if (strpos($message, $error) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gérer l'erreur de base de données
     */
    private function handleDatabaseError(Request $request, $exception)
    {
        // Si c'est une requête AJAX/API, retourner du JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error' => 'Service temporairement indisponible',
                'message' => 'La base de données n\'est pas accessible actuellement.',
                'retry_after' => 30,
                'timestamp' => now()->toISOString()
            ], 503);
        }

        // Pour les requêtes web normales, afficher la page d'erreur
        return response()->view('errors.database-unavailable', [
            'exception' => $exception,
            'timestamp' => now()
        ], 503);
    }
}
