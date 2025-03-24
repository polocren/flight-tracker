<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\AircraftController;
use App\Http\Controllers\WeatherController;

// Routes API
Route::prefix('api')->group(function () {
    // Routes pour l'authentification
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        
        // Routes pour les vols
        Route::apiResource('flights', FlightController::class);
        
        // Route alternative pour la suppression (GET au lieu de DELETE)
        Route::get('/flights/delete/{flight}', [FlightController::class, 'destroyAlternative']);
    });

    // Routes publiques pour les informations sur les avions
    Route::get('/aircraft', [AircraftController::class, 'index']);
    Route::get('/aircraft/{aircraft}', [AircraftController::class, 'show']);
    
    // Routes pour la météo des aéroports
    Route::get('/weather/airport', [WeatherController::class, 'getAirportWeather']);
});

// Route principale pour Vue
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');