<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

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
}
