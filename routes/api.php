<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CategoriesController;
use App\Http\Controllers\User\LocationsController;
use App\Http\Controllers\User\EventController;
use App\Http\Controllers\User\RestaurantController;
use App\Http\Controllers\User\SponsorController;
use App\Http\Controllers\User\AdController;
use App\Http\Controllers\User\SubEmailController;

// Categories endpoints
Route::get("/categories/get", [CategoriesController::class, 'get']);
Route::post("/categories/search", [CategoriesController::class, 'search']);

// Locations endpoints
Route::get("/locations/get", [LocationsController::class, 'get']);
Route::post("/locations/search", [LocationsController::class, 'search']);

// Events endpoints
Route::get("/events/get", [EventController::class, 'get']);
Route::post("/events/search", [EventController::class, 'search']);

// Sponsors endpoints
Route::get("/sponsors/get-top", [SponsorController::class, 'getTop']);
Route::get("/sponsors/get-other", [SponsorController::class, 'getOther']);
Route::post("/sponsors/search-top", [SponsorController::class, 'searchTop']);
Route::post("/sponsors/search-other", [SponsorController::class, 'searchOther']);

// Restaurant endpoints
Route::get("/restaurants/get", [RestaurantController::class, 'get']);
Route::post("/restaurants/search", [RestaurantController::class, 'search']);

// ads endpoints
Route::get("/ads/get", [AdController::class, 'get']);
Route::post("/ads/search", [AdController::class, 'search']);

// email endpoints
Route::post("/subscribe", [SubEmailController::class, 'subscribe']);

