<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $categories = Restaurant::with("location")->latest()->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $categories
            ],
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }

    public function search(Request $request) {
        $search = $request->search ? $request->search : '';
        $categories = Restaurant::with("location")->latest()->where('title', 'like', '%' . $search . '%')->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $categories
            ],
            [
                "search" => "البحث بالاسم"
            ]
        );
    }
}
