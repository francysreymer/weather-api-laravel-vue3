<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\LocationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UnauthorizedActionException;
use Exception;

class LocationController extends Controller
{
    private CONST DEFAULT_ADD_MSG_ERROR = 'An error occurred while creating the location'; 
    private CONST DEFAULT_DELETE_MSG_ERROR = 'An error occurred while deleting the location';

    /**
     * Create a new LocationController instance.
     * 
     * @param LocationServiceInterface $locationService
     */
    public function __construct(private LocationServiceInterface $locationService)
    {
    }

    /**
     * Create a new location with forecasts.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'forecasts' => 'required|array',
                'forecasts.*.date' => 'required|date',
                'forecasts.*.min_temperature' => 'required|numeric',
                'forecasts.*.max_temperature' => 'required|numeric',
                'forecasts.*.condition' => 'required|string|max:255',
            ]);

            // Get user ID from session
            $userId = $request->session()->get('user_id');
            if (!$userId) {
                return response()->json([
                    'message' => 'User not authenticated',
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $locationData = [
                'user_id' => $userId,
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
            ];

            $forecastsData = $validatedData['forecasts'];

            $location = $this->locationService->createLocationWithForecasts($locationData, $forecastsData);

            return response()->json($location, JsonResponse::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => self::DEFAULT_ADD_MSG_ERROR,
                'errors' => $e->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedActionException $e) {
            return response()->json([
                'message' => self::DEFAULT_ADD_MSG_ERROR,
                'error' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (Exception $e) {
            return response()->json([
                'message' => self::DEFAULT_ADD_MSG_ERROR,
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            // Get user ID from session
            $userId = $request->session()->get('user_id');
            if (!$userId) {
                return response()->json([
                    'message' => 'User not authenticated',
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $isDeleted = $this->locationService->deleteLocation($id, $userId);

            if ($isDeleted) {
                return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
            }

            return response()->json(null, JsonResponse::HTTP_NOT_FOUND);
        } catch (UnauthorizedActionException $e) {
            return response()->json([
                'message' => self::DEFAULT_DELETE_MSG_ERROR,
                'error' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}