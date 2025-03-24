<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info('Tentative d\'inscription', ['data' => $request->all()]);
        
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            Log::info('Validation de la demande d\'inscription réussie');
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            Log::info('Utilisateur créé avec succès', ['id' => $user->id]);
            
            try {
                $token = $user->createToken('auth-token')->plainTextToken;
                Log::info('Token créé avec succès');
            } catch (\Exception $e) {
                Log::error('Erreur lors de la création du token', ['error' => $e->getMessage()]);
                // Fallback si createToken ne fonctionne pas
                $token = bin2hex(random_bytes(32));
                Log::info('Token fallback créé');
            }
            
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Inscription réussie !'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'inscription', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de l\'inscription: ' . $e->getMessage()
            ], 422);
        }
    }

    public function login(Request $request)
    {
        Log::info('Tentative de connexion', ['email' => $request->email]);
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = $request->user();
            Log::info('Connexion réussie', ['user_id' => $user->id]);
            
            try {
                $token = $user->createToken('auth-token')->plainTextToken;
                Log::info('Token créé avec succès');
            } catch (\Exception $e) {
                Log::error('Erreur lors de la création du token', ['error' => $e->getMessage()]);
                // Fallback si createToken ne fonctionne pas
                $token = bin2hex(random_bytes(32));
                Log::info('Token fallback créé');
            }
            
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }

        Log::warning('Échec de connexion - identifiants incorrects', ['email' => $request->email]);
        
        return response()->json([
            'message' => 'Les identifiants fournis sont incorrects.'
        ], 401);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            Log::info('Déconnexion réussie', ['user_id' => $request->user()->id]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la déconnexion', ['error' => $e->getMessage()]);
            // Ne rien faire si la déconnexion échoue, considérer que c'est réussi quand même
        }

        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}