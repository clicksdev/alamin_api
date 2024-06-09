<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
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
        });

    });
});
