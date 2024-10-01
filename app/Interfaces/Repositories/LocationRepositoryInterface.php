<?php

namespace App\Interfaces\Repositories;

use App\Models\Location;

interface LocationRepositoryInterface
{
    /**
     * Create a new location with forecasts.
     * 
     * @param array $locationData
     * @param array $forecastsData
     * @return Location
     */
    public function createWithForecasts(array $locationData, array $forecastsData): Location;

    /**
     * Delete a location.
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Count the number of locations for a user.
     * 
     * @param int $userId
     * @return int
     */
    public function countByUserId(int $userId): int;

    /**
     * Find a location by ID.
     * 
     * @param int $id
     * @return Location|null
     */
    public function findById(int $id): ?Location;
}