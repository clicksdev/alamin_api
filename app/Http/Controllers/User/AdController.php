<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Ad;

class AdController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $Ad = Ad::latest()->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $Ad
            ],
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }

    public function search(Request $request) {
        $search = $request->search ? $request->search : '';
        $Ad = Ad::latest()->where('title', 'like', '%' . $search . '%')->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $Ad
            ],
            [
                "search" => "البحث بالاسم"
            ]
        );
    }
}
