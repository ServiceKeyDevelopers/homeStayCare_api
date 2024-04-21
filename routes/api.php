<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\CityController;
use App\Http\Controllers\Front\TeamController;
use App\Http\Controllers\Front\SliderController;
use App\Http\Controllers\Front\CountryController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\BlogPostController;
use App\Http\Controllers\Front\ApplicationController;
use App\Http\Controllers\Front\DesignationController;
use App\Http\Controllers\Front\SocialContactController;
use App\Http\Controllers\Front\BookingServiceController;

// Auth route
Route::post('registration', [AuthController::class, 'registration']);
Route::post('login',        [AuthController::class, 'login']);

// Slider route
Route::get("sliders", [SliderController::class, "index"]);

// Slider route
Route::get("teams",      [TeamController::class, "index"]);
Route::get("teams/{id}", [TeamController::class, "show"]);

// Service route
Route::get('services',      [ServiceController::class, 'index']);
Route::get('services/{id}', [ServiceController::class, 'show']);

// Social contact route
Route::get('social-contacts', [SocialContactController::class, 'index']);

// Service route
Route::get('blog-posts',      [BlogPostController::class, 'index']);
Route::get('blog-posts/{id}', [BlogPostController::class, 'show']);

// Designation route
Route::get('designations', [DesignationController::class, 'index']);

// Country route
Route::get('countries', [CountryController::class, 'index']);

// City route
Route::get('cities', [CityController::class, 'index']);

// Application route
Route::post('applications', [ApplicationController::class, 'store']);

// Booking Service route
Route::post('booking-services', [BookingServiceController::class, 'store']);

Route::middleware("auth:sanctum")->group(function () {

    // Logout route
    Route::post('logout', [AuthController::class, 'logout']);
});

