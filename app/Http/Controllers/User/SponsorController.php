<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Sponsor;

class SponsorController extends Controller
{
    use HandleResponseTrait;

    public function getTop() {
        $Sponsor = Sponsor::where("isTop", 1)->orWhere("isTop", 2)->latest()->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],

                $Sponsor
            ,
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }

    public function searchTop(Request $request) {
        $search = $request->search ? $request->search : '';
        $Sponsor = Sponsor::where("isTop", 1)->latest()->where('name', 'like', '%' . $search . '%')->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],

                $Sponsor
            ,
            [
                "search" => "البحث بالاسم"
            ]
        );
    }
    public function getOther() {
        $Sponsor = Sponsor::where("isTop", false)->latest()->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $Sponsor
            ],
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }

    public function searchOther(Request $request) {
        $search = $request->search ? $request->search : '';
        $Sponsor = Sponsor::where("isTop", 0)->latest()->where('name', 'like', '%' . $search . '%')->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $Sponsor
            ],
            [
                "search" => "البحث بالاسم"
            ]
        );
    }
}
