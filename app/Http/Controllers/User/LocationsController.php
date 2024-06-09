<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Location;

class LocationsController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $locations = Location::with(["events" => function ($q) {
            $q->select("id", "title", "sub_title", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to");
        }])->latest()->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $locations
            ],
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }

    public function search(Request $request) {
        $search = $request->search ? $request->search : '';
        $locations = Location::with(["events" => function ($q) {
            $q->select("id", "title", "sub_title", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to");
        }])->latest()->where('title', 'like', '%' . $search . '%')
        ->orWhere('sub_title', 'like', '%' . $search . '%')->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $locations
            ],
            [
                "search" => "البحث بالعنوان او العنوان الفرعي"
            ]
        );
    }
}
