<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\User\SubEmailController;
use App\Http\Middleware\GuestAdminMiddleware;

Route::prefix('admin')->group(function () {
    Route::post("login", [AuthController::class, "login"])->middleware([GuestAdminMiddleware::class])->name("admin.login.post");
    Route::get("login", [AuthController::class, "index"])->middleware([GuestAdminMiddleware::class])->name("login");

    Route::middleware(['auth:admin'])->group(function () {
        // Dashboard
        Route::get("/dashboard", [DashboardController::class, "index"])->name("admin.dashboard");

        // Categories
        Route::prefix('categories')->group(function () {
            Route::get("/", [CategoryController::class, "index"])->name("admin.categories.show");
            Route::get("/get", [CategoryController::class, "get"])->name("admin.categories.get");
            Route::get("/create", [CategoryController::class, "add"])->name("admin.categories.add");
            Route::post("/create", [CategoryController::class, "create"])->name("admin.categories.create");
            Route::get("/edit/{id}", [CategoryController::class, "edit"])->name("admin.categories.edit");
            Route::post("/update", [CategoryController::class, "update"])->name("admin.categories.update");
            Route::get("/delete/{id}", [CategoryController::class, "deleteIndex"])->name("admin.categories.delete.confirm");
            Route::post("/delete", [CategoryController::class, "delete"])->name("admin.categories.delete");
        });

        // Locations
        Route::prefix('locations')->group(function () {
            Route::get("/", [LocationController::class, "index"])->name("admin.locations.show");
            Route::get("/get", [LocationController::class, "get"])->name("admin.locations.get");
            Route::get("/create", [LocationController::class, "add"])->name("admin.locations.add");
            Route::post("/create", [LocationController::class, "create"])->name("admin.locations.create");
            Route::get("/edit/{id}", [LocationController::class, "edit"])->name("admin.locations.edit");
            Route::post("/update", [LocationController::class, "update"])->name("admin.locations.update");
            Route::get("/delete/{id}", [LocationController::class, "deleteIndex"])->name("admin.locations.delete.confirm");
            Route::post("/delete", [LocationController::class, "delete"])->name("admin.locations.delete");
        });

        // Events
        Route::prefix('events')->group(function () {
            Route::get("/", [EventController::class, "index"])->name("admin.events.show");
            Route::get("/get", [EventController::class, "get"])->name("admin.events.get");
            Route::get("/create", [EventController::class, "add"])->name("admin.events.add");
            Route::post("/create", [EventController::class, "create"])->name("admin.events.create");
            Route::get("/edit/{id}", [EventController::class, "edit"])->name("admin.events.edit");
            Route::post("/update", [EventController::class, "update"])->name("admin.events.update");
            Route::get("/delete/{id}", [EventController::class, "deleteIndex"])->name("admin.events.delete.confirm");
            Route::post("/delete", [EventController::class, "delete"])->name("admin.events.delete");
            Route::post("/set-top", [EventController::class, "setTopEvents"])->name("admin.events.settop");
            Route::get("/get-top", [EventController::class, "getTopEvents"])->name("admin.events.gettop");
        });

        // Sponsor
        Route::prefix('sponsors')->group(function () {
            Route::get("/", [SponsorController::class, "index"])->name("admin.sponsors.show");
            Route::get("/get", [SponsorController::class, "get"])->name("admin.sponsors.get");
            Route::get("/create", [SponsorController::class, "add"])->name("admin.sponsors.add");
            Route::post("/create", [SponsorController::class, "create"])->name("admin.sponsors.create");
            Route::get("/edit/{id}", [SponsorController::class, "edit"])->name("admin.sponsors.edit");
            Route::get("/toggleTop/{id}", [SponsorController::class, "toggleTop"])->name("admin.sponsors.toggleTop");
            Route::post("/update", [SponsorController::class, "update"])->name("admin.sponsors.update");
            Route::get("/delete/{id}", [SponsorController::class, "deleteIndex"])->name("admin.sponsors.delete.confirm");
            Route::post("/delete", [SponsorController::class, "delete"])->name("admin.sponsors.delete");
        });

        // Restaurants
        Route::prefix('restaurants')->group(function () {
            Route::get("/", [RestaurantController::class, "index"])->name("admin.restaurants.show");
            Route::get("/get", [RestaurantController::class, "get"])->name("admin.restaurants.get");
            Route::get("/create", [RestaurantController::class, "add"])->name("admin.restaurants.add");
            Route::post("/create", [RestaurantController::class, "create"])->name("admin.restaurants.create");
            Route::get("/edit/{id}", [RestaurantController::class, "edit"])->name("admin.restaurants.edit");
            Route::post("/update", [RestaurantController::class, "update"])->name("admin.restaurants.update");
            Route::get("/delete/{id}", [RestaurantController::class, "deleteIndex"])->name("admin.restaurants.delete.confirm");
            Route::post("/delete", [RestaurantController::class, "delete"])->name("admin.restaurants.delete");
        });

        // Ads
        Route::prefix('ads')->group(function () {
            Route::get("/", [AdController::class, "index"])->name("admin.ads.show");
            Route::get("/get", [AdController::class, "get"])->name("admin.ads.get");
            Route::get("/create", [AdController::class, "add"])->name("admin.ads.add");
            Route::post("/create", [AdController::class, "create"])->name("admin.ads.create");
            Route::get("/edit/{id}", [AdController::class, "edit"])->name("admin.ads.edit");
            Route::post("/update", [AdController::class, "update"])->name("admin.ads.update");
            Route::get("/delete/{id}", [AdController::class, "deleteIndex"])->name("admin.ads.delete.confirm");
            Route::post("/delete", [AdController::class, "delete"])->name("admin.ads.delete");
        });

        Route::get("/news-emails", function() {
            return view("Admin.newsLatterEmails.index");
        })->name("admin.emails");
        Route::get("/export-emails", [SubEmailController::class, "export"]);

        Route::post('/store-settings', [SettingsController::class, "store"]);
    });
});
