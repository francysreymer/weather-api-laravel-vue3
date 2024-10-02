<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Repositories\LocationRepositoryInterface;
use App\Repositories\LocationRepository;
use App\Interfaces\Services\LocationServiceInterface;
use App\Services\LocationService;
use App\Interfaces\Services\WeatherForecastServiceInterface;
use App\Services\WeatherForecastService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(LocationServiceInterface::class, LocationService::class);
        $this->app->bind(WeatherForecastServiceInterface::class, WeatherForecastService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
