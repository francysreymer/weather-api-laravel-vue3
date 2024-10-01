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
}