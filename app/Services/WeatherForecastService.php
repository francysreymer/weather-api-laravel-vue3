<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\Services\WeatherForecastServiceInterface;

class WeatherForecastService implements WeatherForecastServiceInterface
{
    private const OPENWEATHER_API_URL = 'https://api.openweathermap.org/data/2.5/forecast';
    private const OPENWEATHER_NUMBER_OF_FORECASTS = 3;
    private const OPENWEATHER_UNITS = 'metric';

    /**
     * Get weather forecast data from OpenWeatherMap API.
     * 
     * @param string $city
     * @param string $state
     * @return array{
     *     success: bool,
     *     data: array{
     *         date_forecast: string,
     *         main: string,
     *         description: string,
     *         icon: string,
     *         temperature: float,
     *         feels_like: float,
     *         min_temperature: float,
     *         max_temperature: float,
     *         pressure: int,
     *         humidity: int,
     *         wind_speed: float,
     *         cloudiness: int
     *     }[]
     * }
     * @throws \Exception
     */
    public function getWeatherForecast(string $city, string $state): array
    {
        $apiKey = config('app.openweather_api_key');

        $response = Http::get(self::OPENWEATHER_API_URL, [
            'q' => "{$city},{$state}",
            'appid' => $apiKey,
            'units' => self::OPENWEATHER_UNITS,
            'cnt'   => self::OPENWEATHER_NUMBER_OF_FORECASTS
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $this->formatForecastData($response->json())
            ];
        }

        throw new \Exception('Unable to fetch weather data', $response->status());
    }

    /**
     * Format the forecast data into the desired structure.
     * 
     * @param array $data
     * @return array
     */
    private function formatForecastData(array $data): array
    {
        return array_map(function ($forecast) {
            return [
                'date_forecast' => $forecast['dt_txt'],
                'main' => $forecast['weather'][0]['main'],
                'description' => $forecast['weather'][0]['description'],
                'icon' => $forecast['weather'][0]['icon'],
                'temperature' => $forecast['main']['temp'],
                'feels_like' => $forecast['main']['feels_like'],
                'min_temperature' => $forecast['main']['temp_min'],
                'max_temperature' => $forecast['main']['temp_max'],
                'pressure' => $forecast['main']['pressure'],
                'humidity' => $forecast['main']['humidity'],
                'wind_speed' => $forecast['wind']['speed'],
                'cloudiness' => $forecast['clouds']['all'],
            ];
        }, $data['list']);
    }
}