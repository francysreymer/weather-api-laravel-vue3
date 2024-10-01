<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class WeatherForecastController extends Controller
{
    private const OPENWEATHER_API_URL = 'https://api.openweathermap.org/data/2.5/forecast';
    private const OPENWEATHER_NUMBER_OF_FORECASTS = 3;
    private const OPENWEATHER_UNITS = 'metric';

    /**
     * Fetch weather data from OpenWeatherMap API.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getWeatherForecast(Request $request)
    {
        $city = $request->input('city');
        $state = $request->input('state');
        $apiKey = config('app.openweather_api_key');

        $response = Http::get(self::OPENWEATHER_API_URL, [
            'q' => "{$city},{$state}",
            'appid' => $apiKey,
            'units' => self::OPENWEATHER_UNITS,
            'cnt'   => self::OPENWEATHER_NUMBER_OF_FORECASTS
        ]);

        if ($response->successful()) {
            return response($response->body(), JsonResponse::HTTP_OK)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST')
                ->header('Access-Control-Allow-Headers', 'Content-Type');
        }

        return response()->json(['error' => 'Unable to fetch weather data'], $response->status());
    }
}