<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]);
 
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
 
            // Envoi de l'email de vérification
            event(new Registered($user));
 
            return response()->json([
                'message' => 'Inscription réussie. Veuillez vérifier votre email.',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
                return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Vérification de l'email de l'utilisateur.
     * @param mixed $id
     * @param mixed $hash
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verifyEmail($id, $hash)
    {
        try {
            $user = User::findOrFail($id);
 
            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                return response()->json(['error' => 'Lien de vérification invalide.'], 400);
            }
 
            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email déjà vérifié.'], 200);
            }
 
            $user->markEmailAsVerified();
 
            return response()->json(['message' => 'Email vérifié avec succès.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Connexion d'un utilisateur.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);
 
            $user = User::where('email', $request->email)->first();
 
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Identifiants incorrects.'], 401);
            }
 
            if (!$user->hasVerifiedEmail()) {
                return response()->json(['error' => 'Veuillez vérifier votre email avant de vous connecter.'], 403);
            }
 
            $token = $user->createToken('auth-token')->plainTextToken;
 
            return response()->json(['token' => $token, 'user' => $user], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Données invalides.', 'details' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            if (Auth::check() ) {
                Auth::user()->currentAccessToken()->delete();
                return response()->json(['message' => 'Déconnexion réussie.'], 200);
            } else {
                return response()->json(['error' => 'Vous n\'êtes pas connecté.'], 401);
            }            
        } catch (Exception $e) {
            return response()->json(['error' => 'Impossible de se déconnecter: '. $e->getMessage()], 500);
        }
    }

    /**
     * Envoi d'un lien de réinitialisation de mot de passe.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'string', 'email', 'exists:users,email'],
            ]);
 
            Password::sendResetLink($validated);
 
            return response()->json(['message' => 'Lien de réinitialisation envoyé.'], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Données invalides.', 'details' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }
}
