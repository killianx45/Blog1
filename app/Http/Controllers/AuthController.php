<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\LoginRequest;

class AuthController extends Controller
{
    /**
     * Créer une nouvelle instance de AuthController.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Obtenir un JWT via les identifiants fournis.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        Log::info('Tentative de connexion avec les identifiants', ['email' => $credentials['email']]);

        if (!$token = auth('api')->attempt($credentials)) {
            Log::warning('Echec de authentification: identifiants invalides');
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = Auth::guard('api')->user();
        if ($user->role !== 'ROLE_ADMIN') {
            Auth::guard('api')->logout();
            Log::warning('Echec de authentification: utilisateur non admin', ['role' => $user->role]);
            return response()->json(['error' => 'Accès refusé. Seuls les administrateurs peuvent se connecter.'], 403);
        }

        Log::info('Authentification réussie', ['user_id' => $user->id]);
        return $this->respondWithToken($token);
    }

    /**
     * Obtenir l'utilisateur authentifié.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Déconnexion de l'utilisateur (invalidation du token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    /**
     * Rafraîchir un token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return $this->respondWithToken($newToken);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token invalide ou expiré'], 401);
        }
    }

    /**
     * Obtenir la structure de tableau du token.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => auth('api')->user()
        ]);
    }

    /**
     * Méthode statique pour API Platform
     * 
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function apiLogin($data): \Illuminate\Http\JsonResponse
    {
        Log::info('Appel à apiLogin', ['data_type' => gettype($data), 'data' => is_object($data) ? get_class($data) : 'non-objet']);

        $controller = new self();

        try {
            // Vérifier si $data est un objet LoginRequest ou un tableau
            if (is_object($data) && $data instanceof LoginRequest) {
                Log::info('Données reçues comme objet LoginRequest');
                $email = $data->email;
                $password = $data->password;
            } elseif (is_array($data)) {
                Log::info('Données reçues comme tableau');
                $email = $data['email'] ?? null;
                $password = $data['password'] ?? null;
            } elseif (is_object($data) && method_exists($data, 'all')) {
                // Cas où $data est un objet Request
                Log::info('Données reçues comme objet Request');
                $requestData = $data->all();
                $email = $requestData['email'] ?? null;
                $password = $requestData['password'] ?? null;
            } else {
                Log::error('Format de données invalide dans apiLogin', [
                    'type' => gettype($data),
                    'class' => is_object($data) ? get_class($data) : 'non-objet'
                ]);
                return response()->json(['error' => 'Format de données invalide'], 400);
            }

            if (!$email || !$password) {
                Log::error('Données manquantes dans apiLogin', [
                    'email_exists' => (bool)$email,
                    'password_exists' => (bool)$password
                ]);
                return response()->json(['error' => 'Email et mot de passe requis'], 400);
            }

            $request = new Request();
            $request->merge([
                'email' => $email,
                'password' => $password
            ]);

            Log::info('Délégation à la méthode login', ['email' => $email]);
            return $controller->login($request);
        } catch (\Exception $e) {
            Log::error('Exception dans apiLogin', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erreur lors de la connexion: ' . $e->getMessage()], 500);
        }
    }
}
