<?php

namespace App\Services;

use App\Interfaces\Repositories\LocationRepositoryInterface;
use App\Interfaces\Services\LocationServiceInterface;
use App\Models\Location;
use App\Exceptions\UnauthorizedActionException;

class LocationService implements LocationServiceInterface
{
    private CONST MAX_LOCATIONS_PER_USER = 3;

    /**
     * Create a new LocationService instance.
     *
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(private LocationRepositoryInterface $locationRepository)
    {
    }

    /**
     * Get the weather forecast for a location.
     *
     * @param array $locationData
     * @param array $forecastsData
     * @return Location
     * @throws UnauthorizedActionException
     */
    public function createLocationWithForecasts(array $locationData, array $forecastsData): Location
    {
        $userId = $locationData['user_id'];
        
        // Check if the user already has 3 locations
        $locationCount = $this->locationRepository->countByUserId($userId);
        if ($locationCount >= self::MAX_LOCATIONS_PER_USER) {
            throw new UnauthorizedActionException('User can only register up to 3 locations');     
        }

        return $this->locationRepository->createWithForecasts($locationData, $forecastsData);
    }

    /**
     * Delete a location.
     * 
     * @param int $id
     * @param int $userId
     * @return bool
     * @throws UnauthorizedActionException
     */
    public function deleteLocation(int $id, int $userId): bool
    {
        $location = $this->locationRepository->findById($id);
        if (!$location || $location->user_id !== $userId) {
            throw new UnauthorizedActionException();
        }

        return $this->locationRepository->delete($id);
    }

    /**
     * Get a location by ID.
     * 
     * @param int $id
     * @return Location|null
     */
    public function getLocationById(int $id): ?Location
    {
        return $this->locationRepository->findById($id);
    }
}