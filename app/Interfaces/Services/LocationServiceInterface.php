<?php

namespace App\Interfaces\Services;

use App\Models\Location;

interface LocationServiceInterface
{
    /**
     * Create a new location with forecasts.
     * 
     * @param array $locationData
     * @param array $forecastsData
     * @return Location
     */
    public function createLocationWithForecasts(array $locationData, array $forecastsData): Location;

    /**
     * Delete a location.
     * 
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function deleteLocation(int $id, int $userId): bool;

    /**
     * Get the weather forecast for a location by specific user id.
     *
     * @param int $userId
     * @return array
     */
    public function getLocationsWithForecastsByUserId(int $userId): array;

    /**
     * Get the weather forecast for a location by specific location id.
     *
     * @param int $id
     * @param int $userId
     * @return Location
     */
    public function getLocationWithForecastsById(int $id, int $userId): Location;
}