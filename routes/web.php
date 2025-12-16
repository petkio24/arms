<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\WorkstationController;

// Стандартные маршруты аутентификации Laravel
Auth::routes();

// Защищенные маршруты
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Locations
    Route::resource('locations', LocationController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Components
    Route::resource('components', ComponentController::class);
    Route::post('components/{component}/install', [ComponentController::class, 'install'])
        ->name('components.install');
    Route::post('components/{component}/remove', [ComponentController::class, 'remove'])
        ->name('components.remove');

    // Workstations
    Route::resource('workstations', WorkstationController::class);
    Route::get('workstations/{workstation}/compare', [WorkstationController::class, 'compare'])
        ->name('workstations.compare');
    Route::post('workstations/{workstation}/save-initial', [WorkstationController::class, 'saveInitialConfig'])
        ->name('workstations.save-initial');
    Route::get('workstations/{workstation}/history', [WorkstationController::class, 'history'])
        ->name('workstations.history');
    Route::post('workstations/{workstation}/change-status', [WorkstationController::class, 'changeStatus'])
        ->name('workstations.change-status');
});
