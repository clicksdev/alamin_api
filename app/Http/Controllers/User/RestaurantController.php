<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Restaurant;
use App\Models\Event;

class RestaurantController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $categories = Restaurant::with("location")->get();

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
        $service = Restaurant::with(["location"])
                       ->find($request->id);
        if($service) {
            $related = Event::where("location_id", $service->location_id)->get();
            $service->relatedEvants = $related;
        }
        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
                $service
            ,
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }

    public function search(Request $request) {
        $search = $request->search ? $request->search : '';
        $categories = Restaurant::with("location")->where('title', 'like', '%' . $search . '%')->get();

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
