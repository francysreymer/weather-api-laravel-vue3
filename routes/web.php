<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocationFrontendController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/locations', [LocationFrontendController::class, 'index'])->name('locations.index');
    Route::get('/locations/add', [LocationFrontendController::class, 'create'])->name('locations.create');
    Route::get('/locations/view/{id}', [LocationFrontendController::class, 'show'])->name('locations.show');
});

require __DIR__.'/auth.php';
