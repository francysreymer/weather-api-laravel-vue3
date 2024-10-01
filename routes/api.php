<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\WeatherForecastController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/locations', [LocationController::class, 'create']);
    Route::get('/weather-forecast', [WeatherForecastController::class, 'getWeatherForecast']);
    Route::delete('/locations/{id}', [LocationController::class, 'delete']);
});
