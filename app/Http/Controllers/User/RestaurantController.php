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

                $categories
            ,
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }
    public function service(Request $request) {
        $events = Restaurant::latest()
                       ->with(['relatedEvants' => function($query) {
                        $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                              ->where('date_to', '>=', now()); // Ensure active events only
                        }, "location"])
                       ->find($request->id);

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
                $events
            ,
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

                $categories
            ,
            [
                "search" => "البحث بالاسم"
            ]
        );
    }
}
