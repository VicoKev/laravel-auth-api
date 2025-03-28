<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentification
Route::post('/register', [App\Http\Controllers\AuthApiController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthApiController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\AuthApiController::class, 'logout'])->middleware('auth:sanctum');

// Vérification de l'email
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\AuthApiController::class, 'verifyEmail'])->name('verification.verify');
