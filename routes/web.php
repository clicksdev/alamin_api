<?php

use Illuminate\Support\Facades\Route;
include(base_path('routes/admin.php'));

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logout', function () {
    Auth::guard('admin')->logout();
    return redirect('/admin/login');
});
