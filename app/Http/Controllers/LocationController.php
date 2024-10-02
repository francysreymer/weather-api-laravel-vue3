<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\LocationServiceInterface;
use App\Http\Requests\CreateLocationRequest;
use App\Interfaces\Services\WeatherForecastServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UnauthorizedActionException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class LocationController extends Controller
{
    /**
     * Create a new LocationController instance.
     * 
     * @param LocationServiceInterface $locationService
     * @param WeatherForecastServiceInterface $weatherForecastService
     */
    public function __construct(private LocationServiceInterface $locationService, private WeatherForecastServiceInterface $weatherForecastService)
    {
    }

    /**
     * Create a new location with forecasts.
     * 
     * @param CreateLocationRequest $request
     * @return JsonResponse
     */
    public function create(CreateLocationRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $forecasts = $this->weatherForecastService->getWeatherForecast($validatedData['city'], $validatedData['state']);
            $forecastsData = $forecasts['data'];

            Validator::make(['forecasts' => $forecastsData], [
                'forecasts' => 'required|array',
                'forecasts.*.date_forecast' => 'required|date',
                'forecasts.*.main' => 'required|string|max:255',
                'forecasts.*.description' => 'required|string|max:255',
                'forecasts.*.icon' => 'required|string|max:255',
                'forecasts.*.temperature' => 'required|numeric',
                'forecasts.*.feels_like' => 'required|numeric',
                'forecasts.*.min_temperature' => 'required|numeric',
                'forecasts.*.max_temperature' => 'required|numeric',
                'forecasts.*.pressure' => 'required|numeric',
                'forecasts.*.humidity' => 'required|numeric',
                'forecasts.*.wind_speed' => 'required|numeric',
                'forecasts.*.cloudiness' => 'required|numeric',
            ])->validate();
            
            $locationData = [
                'user_id' => Auth::id(),
                'name' => sprintf('%s, %s', $validatedData['city'], $validatedData['state']),
            ];

            $location = $this->locationService->createLocationWithForecasts($locationData, $forecastsData);

            return response()->json($location, JsonResponse::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedActionException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a location.
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $isDeleted = $this->locationService->deleteLocation($id, Auth::id());

            if ($isDeleted) {
                return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
            }

            return response()->json(null, JsonResponse::HTTP_NOT_FOUND);
        } catch (UnauthorizedActionException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}