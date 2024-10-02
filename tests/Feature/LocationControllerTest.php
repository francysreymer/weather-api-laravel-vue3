<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Interfaces\Services\WeatherForecastServiceInterface;
use App\Services\LocationService;
use App\Interfaces\Repositories\LocationRepositoryInterface;
use App\Exceptions\UnauthorizedActionException;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\User;
use App\Models\Location;
use Mockery;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the create method of LocationController.
     *
     * @return void
     */
    public function test_create_location_with_forecasts()
    {
        $this->withoutExceptionHandling();

        // Create a user
        $user = User::factory()->create();

        // Act as the created user
        $this->actingAs($user);

        // Mock the WeatherForecastService
        $mockWeatherForecastService = Mockery::mock(WeatherForecastServiceInterface::class);
        $mockWeatherForecastService->shouldReceive('getWeatherForecast')
            ->with('London', 'uk')
            ->andReturn([
                'success' => true,
                'data' => [
                    [
                        'date_forecast' => '2024-10-02 03:00:00',
                        'main' => 'Rain',
                        'description' => 'light rain',
                        'icon' => '10n',
                        'temperature' => 13.06,
                        'feels_like' => 12.8,
                        'min_temperature' => 13.03,
                        'max_temperature' => 13.06,
                        'pressure' => 1008,
                        'humidity' => 91,
                        'wind_speed' => 4.24,
                        'cloudiness' => 100,
                    ],
                    [
                        'date_forecast' => '2024-10-02 06:00:00',
                        'main' => 'Rain',
                        'description' => 'light rain',
                        'icon' => '10n',
                        'temperature' => 13.06,
                        'feels_like' => 12.8,
                        'min_temperature' => 13.03,
                        'max_temperature' => 13.06,
                        'pressure' => 1008,
                        'humidity' => 91,
                        'wind_speed' => 4.24,
                        'cloudiness' => 100,
                    ],
                    [
                        'date_forecast' => '2024-10-02 09:00:00',
                        'main' => 'Rain',
                        'description' => 'light rain',
                        'icon' => '10n',
                        'temperature' => 13.06,
                        'feels_like' => 12.8,
                        'min_temperature' => 13.03,
                        'max_temperature' => 13.06,
                        'pressure' => 1008,
                        'humidity' => 91,
                        'wind_speed' => 4.24,
                        'cloudiness' => 100,
                    ],
                ],
            ]);

        // Bind the mock to the service container
        $this->app->instance(WeatherForecastServiceInterface::class, $mockWeatherForecastService);

        // Send a POST request to the create endpoint
        $response = $this->postJson('/api/locations', [
            'city' => 'London',
            'state' => 'uk',
        ]);

        // Assert the response status
        $response->assertStatus(Response::HTTP_CREATED);

        // Assert the response structure
        $response->assertJsonStructure([
            'id',
            'user_id',
            'name',
            'created_at',
            'updated_at',
        ]);

        $this->assertDatabaseHas('locations', [
            'name' => 'London, uk',
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseCount('locations', 1);
        $this->assertDatabaseCount('forecasts', 3);
    }

    /**
     * Test the create method of LocationController with validation error.
     *
     * @return void
     */
    public function test_create_location_with_forecasts_validation_error()
    {
        $this->withoutExceptionHandling();

        // Create a user
        $user = User::factory()->create();

        // Act as the created user
        $this->actingAs($user);

        // Mock the WeatherForecastService
        $mockWeatherForecastService = Mockery::mock(WeatherForecastServiceInterface::class);
        $mockWeatherForecastService->shouldReceive('getWeatherForecast')
            ->with('London', 'uk')
            ->andReturn([
                'success' => true,
                'data' => [
                    [
                        'date_forecast' => '2024-10-02 03:00:00',
                        'main' => 'Rain',
                        'description' => 'light rain',
                        'icon' => '10n',
                        'temperature' => 13.06,
                        'feels_like' => 12.8,
                        'min_temperature' => 13.03,
                        'max_temperature' => 13.06,
                        'pressure' => 1008,
                        'humidity' => 91,
                        'wind_speed' => 4.24,
                        'cloudiness' => 100,
                    ],
                    [
                        'date_forecast' => '2024-10-02 06:00:00',
                        'main' => 'Rain',
                        'description' => 'light rain',
                        'icon' => '10n',
                        'temperature' => 13.06,
                        'feels_like' => 12.8,
                        'min_temperature' => 13.03,
                        'max_temperature' => 13.06,
                        'pressure' => 1008,
                        'humidity' => 91,
                        'wind_speed' => 4.24,
                        'cloudiness' => 100,
                    ],
                    [
                        'date_forecast' => '2024-10-02 09:00:00',
                        'main' => 'Rain',
                        'description' => 'light rain',
                        'icon' => '10n',
                        'temperature' => 13.06,
                        'feels_like' => 12.8,
                        'min_temperature' => 13.03,
                        'max_temperature' => 13.06,
                        'pressure' => 1008,
                        'humidity' => 91,
                        'wind_speed' => 4.24,
                        'cloud' => 100,
                    ],
                ],
            ]);

        // Bind the mock to the service container
        $this->app->instance(WeatherForecastServiceInterface::class, $mockWeatherForecastService);

        // Send a POST request to the create endpoint
        $response = $this->postJson('/api/locations', [
            'city' => 'London',
            'state' => 'uk',
        ]);

        // Assert the response status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Assert the response structure
        $response->assertJsonStructure([
            'errors' => [
                'forecasts.2.cloudiness',
            ],
        ]);

        $this->assertDatabaseCount('locations', 0);
        $this->assertDatabaseCount('forecasts', 0);
    }

    /**
     * Test that an UnauthorizedActionException is thrown when the user has reached the maximum number of locations.
     *
     * @return void
     */
    public function test_create_user_cannot_register_more_than_max_locations()
    {
        // Define the user ID and maximum locations
        $userId = 1;
        $maxLocations = 3;

        // Mock the LocationRepository
        $mockLocationRepository = Mockery::mock(LocationRepositoryInterface::class);
        $mockLocationRepository->shouldReceive('countByUserId')
            ->with($userId)
            ->andReturn($maxLocations);

        // Create an instance of LocationService with the mocked repository
        $locationService = new LocationService($mockLocationRepository);

        // Expect the UnauthorizedActionException to be thrown
        $this->expectException(UnauthorizedActionException::class);
        $this->expectExceptionMessage('User can only register up to 3 locations');

        $locationData = [
            'user_id' => $userId,
            'name' => 'London, uk',
        ];

        $forecastsData = [
            [
                'date_forecast' => '2024-10-02 03:00:00',
                'main' => 'Rain',
                'description' => 'light rain',
                'icon' => '10n',
                'temperature' => 13.06,
                'feels_like' => 12.8,
                'min_temperature' => 13.03,
                'max_temperature' => 13.06,
                'pressure' => 1008,
                'humidity' => 91,
                'wind_speed' => 4.24,
                'cloudiness' => 100,
            ],
        ];

        // Call the method that contains the logic to be tested
        $locationService->createLocationWithForecasts($locationData, $forecastsData);
    }

    /**
     * Test the error validation for location creation.
     * 
     * @return void
     */
    public function test_create_location_error_validation() 
    {
         // Create a user
         $user = User::factory()->create();

         // Act as the created user
         $this->actingAs($user);
 
         // Mock the WeatherForecastService
         $mockWeatherForecastService = Mockery::mock(WeatherForecastService::class);
         $mockWeatherForecastService->shouldReceive('getWeatherForecast')
             ->andReturn([
                 'data' => [
                     [
                         'date_forecast' => '2024-10-02 03:00:00',
                         'main' => 'Rain',
                         'description' => 'light rain',
                         'icon' => '10n',
                         'temperature' => 13.06,
                         'feels_like' => 12.8,
                         'min_temperature' => 13.03,
                         'max_temperature' => 13.06,
                         'pressure' => 1008,
                         'humidity' => 91,
                         'wind_speed' => 4.24,
                         'cloudiness' => 100,
                     ],
                     // Add more forecast data as needed
                 ],
             ]);
 
         // Bind the mock to the service container
         $this->app->instance(WeatherForecastService::class, $mockWeatherForecastService);
 
         // Send a POST request with invalid data
         $response = $this->postJson('/api/locations', [
             'city' => '', // Invalid city
             'state' => '', // Invalid state
         ]);
 
         // Assert the response status
         $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
 
         // Assert the response structure
         $response->assertJsonStructure([
             'errors' => [
                 'city',
                 'state',
             ],
         ]);
    }

    /**
     * Test the delete method of LocationController.
     *
     * @return void
     */
    public function test_delete_location()
    {
        $this->withoutExceptionHandling();

        // Create a user
        $user = User::factory()->create();

        // Act as the created user
        $this->actingAs($user);

        // Create a location
        $location = Location::factory()->create([
            'user_id' => $user->id, // Ensure the location belongs to the authenticated user
        ]);

        // Send a DELETE request to the delete endpoint
        $response = $this->deleteJson("/api/locations/{$location->id}");

        // Assert the response status
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        // Assert the location is missing from the database
        $this->assertDatabaseMissing('locations', [
            'id' => $location->id
        ]);
    }
}