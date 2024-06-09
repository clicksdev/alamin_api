<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CategoriesController;
use App\Http\Controllers\User\LocationsController;
use App\Http\Controllers\User\EventController;

// Categories endpoints
Route::get("/categories/get", [CategoriesController::class, 'get']);
Route::post("/categories/search", [CategoriesController::class, 'search']);

// Locations endpoints
Route::get("/locations/get", [LocationsController::class, 'get']);
Route::post("/locations/search", [LocationsController::class, 'search']);

// Events endpoints
Route::get("/events/get", [EventController::class, 'get']);
Route::post("/events/search", [EventController::class, 'search']);

