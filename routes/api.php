<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

route::prefix('v1')->group(function () {
    route::apiResource('cast', App\Http\Controllers\Api\CastController::class);
    route::apiResource('cast-movie', App\Http\Controllers\Api\CastMovieController::class);
    route::apiResource('genre', App\Http\Controllers\Api\GenreController::class);
    route::apiResource('movie', App\Http\Controllers\Api\MovieController::class);
    route::apiResource('role', App\Http\Controllers\Api\RoleController::class)->middleware(['auth:api', 'isAdmin']);
    route::prefix('auth')->group(function () {
        route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
        route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
        route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('auth:api');
        route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:api');
        route::post('/update', [App\Http\Controllers\Api\AuthController::class, 'update'])->middleware('auth:api');
        route::post('/verifikasi-akun', [App\Http\Controllers\Api\AuthController::class, 'verify'])->middleware('auth:api');
        route::post('/generate-otp-code', [App\Http\Controllers\Api\AuthController::class, 'generateOtpCode'])->middleware('auth:api');
    })->middleware('api');
    route::post('/profile', [App\Http\Controllers\Api\ProfileController::class, 'storeupdate'])->middleware(['auth:api', 'isEmailVerified']);
    route::post('/review', [App\Http\Controllers\Api\ReviewController::class, 'storeupdate'])->middleware(['auth:api', 'isEmailVerified']);
});
