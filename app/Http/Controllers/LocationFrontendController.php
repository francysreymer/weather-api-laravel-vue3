<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\LocationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Exception;

class LocationFrontendController extends Controller
{
    /**
     * Create a new LocationController instance.
     * 
     * @param LocationServiceInterface $locationService
     */
    public function __construct(private LocationServiceInterface $locationService)
    {
    }

    /**
     * Display the form to create a new location with the forecasts.
     * 
     * @return \Inertia\Response
     * @throws Exception
     */
    public function create()
    {
        try {
            return Inertia::render('Locations/Form');
        } catch (Exception $e) {
            return Inertia::render('Error', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the locations with forecasts by logged user ID.
     * 
     * @param Request $request
     * @return \Inertia\Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        try {
            $locations = $this->locationService->getLocationsWithForecastsByUserId(Auth::id());

            return Inertia::render('Locations/Index', [
                'locations' => $locations,
                'token' => $request->session()->get('token'),
            ]);
        } catch (Exception $e) {
            return Inertia::render('Error', [
                'message' => 'An error occurred while fetching the locations',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the location with forecasts by ID.
     * 
     * @param int $id
     * @return \Inertia\Response
     * @throws Exception
     */
    public function show(int $id)
    {
        try {
            $location = $this->locationService->getLocationWithForecastsById($id, Auth::id());

            return Inertia::render('Locations/Show', [
                'location' => $location,
            ]);
        } catch (Exception $e) {
            return Inertia::render('Error', [
                'message' => 'An error occurred while fetching the location',
                'error' => $e->getMessage(),
            ]);
        }
    }
}