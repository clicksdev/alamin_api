<?php

use Illuminate\Support\Facades\Route;
include(base_path('routes/admin.php'));

Route::get('/', function () {
    return view('welcome');
});
