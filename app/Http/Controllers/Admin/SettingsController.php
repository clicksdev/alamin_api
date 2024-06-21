<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
class SettingsController extends Controller
{

    public function store(Request $request)
    {
        foreach ($request->except('_token', "main_restaurants") as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->main_restaurants)
            Setting::updateOrCreate(['key' => "main_restaurants"], ['value' => json_encode($request->main_restaurants)]);


        return redirect()->to('/admin/dashboard')
        ->with('success', 'Product added successfuly');
    }

}
