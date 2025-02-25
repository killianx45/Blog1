<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Parser\AuthHeaders;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Liste des routes qui ne nécessitent pas d'authentification
     */
    protected $exceptRoutes = [
        'api/docs',
        'api/login',
        'api/auth/login',
        'api/contexts',
        'api/.well-known',
        'api/refresh',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $currentPath = $request->path();

        Log::info('JwtMiddleware: Vérification de la route', [
            'path' => $currentPath,
            'method' => $request->method(),
            'has_authorization' => $request->hasHeader('Authorization'),
            'all_headers' => $request->headers->all()
        ]);

        if ($currentPath === 'api' && $request->isMethod('GET')) {
            Log::info('JwtMiddleware: Accès autorisé à la page d\'accueil API');
            return $next($request);
        }

        foreach ($this->exceptRoutes as $route) {
            if (str_starts_with($currentPath, $route)) {
                Log::info('JwtMiddleware: Route exclue de l\'authentification', ['route' => $route]);
                return $next($request);
            }
        }

        try {
            $token = null;

            if ($request->hasHeader('Authorization')) {
                $authHeader = $request->header('Authorization');
                Log::info('JwtMiddleware: En-tête Authorization trouvé', ['header' => $authHeader]);
                if (preg_match('/^bearer\s+(.*)$/i', $authHeader, $matches)) {
                    $token = $matches[1];
                    Log::info('JwtMiddleware: Token extrait avec succès du header Authorization');
                }
            }

            if (!$token && $request->headers->has('X-Authorization')) {
                $authHeader = $request->headers->get('X-Authorization');
                Log::info('JwtMiddleware: En-tête X-Authorization trouvé', ['header' => $authHeader]);

                if (preg_match('/^bearer\s+(.*)$/i', $authHeader, $matches)) {
                    $token = $matches[1];
                    Log::info('JwtMiddleware: Token extrait avec succès du header X-Authorization');
                } else {
                    $token = $authHeader;
                    Log::info('JwtMiddleware: Token brut extrait du header X-Authorization');
                }
            }

            if (!$token && $request->has('token')) {
                $token = $request->input('token');
                Log::info('JwtMiddleware: Token trouvé dans les paramètres de requête');
            }

            if (!$token) {
                Log::warning('JwtMiddleware: Aucun token trouvé');
                return response()->json(['error' => 'Token d\'autorisation non trouvé'], 401);
            }

            JWTAuth::setToken($token);

            Log::info('JwtMiddleware: Tentative d\'authentification avec le token', ['token_length' => strlen($token)]);

            $user = JWTAuth::authenticate();

            if (!$user) {
                Log::warning('JwtMiddleware: Utilisateur non trouvé avec le token fourni');
                return response()->json(['error' => 'Utilisateur non trouvé'], 401);
            }

            if ($user->role !== 'ROLE_ADMIN') {
                Log::warning('JwtMiddleware: Accès refusé - rôle insuffisant', ['role' => $user->role]);
                return response()->json(['error' => 'Accès non autorisé. Rôle administrateur requis.'], 403);
            }

            Log::info('JwtMiddleware: Authentification réussie', ['user_id' => $user->id, 'role' => $user->role]);

            $request->attributes->set('auth_user', $user);
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                Log::warning('JwtMiddleware: Token invalide', ['exception' => get_class($e), 'message' => $e->getMessage()]);
                return response()->json(['error' => 'Token invalide'], 401);
            } else if ($e instanceof TokenExpiredException) {
                Log::warning('JwtMiddleware: Token expiré', ['exception' => get_class($e), 'message' => $e->getMessage()]);
                return response()->json(['error' => 'Token expiré'], 401);
            } else {
                Log::warning('JwtMiddleware: Erreur d\'authentification', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => 'Erreur d\'authentification: ' . $e->getMessage()], 401);
            }
        }

        return $next($request);
    }
}
