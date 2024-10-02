<?php

namespace App\Interfaces\Services;

interface WeatherForecastServiceInterface
{
    /**
     * Get weather forecast data from OpenWeatherMap API.
     * 
     * @param string $city
     * @param string $state
     * @return array
     * @throws \Exception
     */
    public function getWeatherForecast(string $city, string $state): array;
}