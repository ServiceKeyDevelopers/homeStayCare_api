<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\SocialContactController;

Route::get("/clear", function () {
    Artisan::call("optimize:clear");

    return "Success! Your are very lucky!";
});

// Auth route
Route::post('login', [AuthController::class, 'login']);

Route::middleware("auth:sanctum")->group(function() {
    // User route
    Route::get('users',            [UserController::class, 'index']);
    Route::post('users',           [UserController::class, 'store']);
    Route::get('users/permission', [UserController::class, 'userPermission']);
    Route::get('users/{id}',       [UserController::class, 'show']);
    Route::put('users/{id}',       [UserController::class, 'update']);

    // Category route
    Route::get('categories',         [CategoryController::class, 'index']);
    Route::post('categories',        [CategoryController::class, 'store']);
    Route::get('categories/{id}',    [CategoryController::class, 'show']);
    Route::put('categories/{id}',    [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'delete']);

    // Tag route
    Route::get('tags',         [TagController::class, 'index']);
    Route::post('tags',        [TagController::class, 'store']);
    Route::get('tags/{id}',    [TagController::class, 'show']);
    Route::put('tags/{id}',    [TagController::class, 'update']);
    Route::delete('tags/{id}', [TagController::class, 'delete']);

    // Status route
    Route::get('statuses', [StatusController::class, 'index']);
    Route::post('statuses', [StatusController::class, 'store']);
    Route::get('statuses/{id}', [StatusController::class, 'show']);
    Route::put('statuses/{id}', [StatusController::class, 'update']);
    Route::delete('statuses/{id}', [StatusController::class, 'destroy']);

    // Blog post route
    Route::get('blog-posts',         [BlogPostController::class, 'index']);
    Route::post('blog-posts',        [BlogPostController::class, 'store']);
    Route::get('blog-posts/{id}',    [BlogPostController::class, 'show']);
    Route::put('blog-posts/{id}',    [BlogPostController::class, 'update']);
    Route::delete('blog-posts/{id}', [BlogPostController::class, 'delete']);

    // Role route
    Route::get('roles',      [RoleController::class, 'index']);
    Route::post('roles',     [RoleController::class, 'store']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::put('roles/{id}', [RoleController::class, 'update']);

    // Permission route
    Route::get('permissions',      [PermissionController::class, 'index']);
    Route::post('permissions',     [PermissionController::class, 'store']);
    Route::get('permissions/{id}', [PermissionController::class, 'show']);
    Route::put('permissions/{id}', [PermissionController::class, 'update']);

    // Slider route
    Route::get('sliders',      [SliderController::class, 'index']);
    Route::post('sliders',     [SliderController::class, 'store']);
    Route::get('sliders/{id}', [SliderController::class, 'show']);
    Route::put('sliders/{id}', [SliderController::class, 'update']);

    // Team route
    Route::get('teams',         [TeamController::class, 'index']);
    Route::post('teams',        [TeamController::class, 'store']);
    Route::get('teams/{id}',    [TeamController::class, 'show']);
    Route::put('teams/{id}',    [TeamController::class, 'update']);
    Route::delete('teams/{id}', [TeamController::class, 'delete']);

    // Service route
    Route::get('services',         [ServiceController::class, 'index']);
    Route::post('services',        [ServiceController::class, 'store']);
    Route::get('services/{id}',    [ServiceController::class, 'show']);
    Route::put('services/{id}',    [ServiceController::class, 'update']);
    Route::delete('services/{id}', [ServiceController::class, 'delete']);

    // Social contact route
    Route::get('social-contacts',         [SocialContactController::class, 'index']);
    Route::post('social-contacts',        [SocialContactController::class, 'store']);
    Route::get('social-contacts/{id}',    [SocialContactController::class, 'show']);
    Route::put('social-contacts/{id}',    [SocialContactController::class, 'update']);
    Route::delete('social-contacts/{id}', [SocialContactController::class, 'delete']);

    // Designation route
    Route::get('designations',         [DesignationController::class, 'index']);
    Route::post('designations',        [DesignationController::class, 'store']);
    Route::get('designations/{id}',    [DesignationController::class, 'show']);
    Route::put('designations/{id}',    [DesignationController::class, 'update']);
    Route::delete('designations/{id}', [DesignationController::class, 'delete']);

    // Country route
    Route::get('countries',         [CountryController::class, 'index']);
    Route::post('countries',        [CountryController::class, 'store']);
    Route::get('countries/{id}',    [CountryController::class, 'show']);
    Route::put('countries/{id}',    [CountryController::class, 'update']);
    Route::delete('countries/{id}', [CountryController::class, 'delete']);

    // City route
    Route::get('cities',         [CityController::class, 'index']);
    Route::post('cities',        [CityController::class, 'store']);
    Route::get('cities/{id}',    [CityController::class, 'show']);
    Route::put('cities/{id}',    [CityController::class, 'update']);
    Route::delete('cities/{id}', [CityController::class, 'delete']);

    // Country route
    Route::get('applications',         [ApplicationController::class, 'index']);
    Route::get('applications/{id}',    [ApplicationController::class, 'show']);
    Route::delete('applications/{id}', [ApplicationController::class, 'delete']);

    Route::post('logout', [AuthController::class, 'logout']);
});
