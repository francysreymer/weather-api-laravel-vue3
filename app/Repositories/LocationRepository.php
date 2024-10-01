<?php

namespace App\Repositories;

use App\Models\Location;
use App\Models\Forecast;
use App\Interfaces\Repositories\LocationRepositoryInterface;
use Illuminate\Database\DatabaseManager;

class LocationRepository implements LocationRepositoryInterface
{
    public function __construct(
        private Location $location,
        private Forecast $forecast,
        private DatabaseManager $db
    ) {}

    /**
     * Create a new location with forecasts.
     * 
     * @param array $locationData
     * @param array $forecastsData
     * @return Location
     */
    public function createWithForecasts(array $locationData, array $forecastsData): Location
    {
        return $this->db->transaction(function () use ($locationData, $forecastsData) {
            $location = $this->location->create($locationData);

            $forecasts = array_map(function ($forecastData) {
                return new $this->forecast($forecastData);
            }, $forecastsData);

            $location->forecasts()->saveMany($forecasts);

            return $location;
        });
    }

    /**
     * Delete a location.
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $location = $this->location->findOrFail($id);
        return (bool) $location->delete();
    }

    /**
     * Count the number of locations for a user.
     * 
     * @param int $userId
     * @return int
     */
    public function countByUserId(int $userId): int
    {
        return $this->location->where('user_id', $userId)->count();
    }

    /**
     * Find a location by ID.
     * 
     * @param int $id
     * @return Location|null
     */
    public function findById(int $id): ?Location
    {
        return $this->location->find($id);
    }
}