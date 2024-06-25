<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Category;

class CategoriesController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $categories = Category::with(["events" => function($q) {
            $q->with("location");
        }])->get();

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

    public function category() {
        $categories = Category::with(["events" => function($q) {
            $q->with("location");
        }])->find($request->id);

        if ($categories)
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

            return $this->handleResponse(
                false,
                "عملية فاشلة",
                ["Id Not Valid"],
                []
                ,
                [
                    "يبدا مسار الصورة من بعد الدومين مباشرا"
                ]
            );
    }

    public function search(Request $request) {
        $search = $request->search ? $request->search : '';
        $categories = Category::where('title', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],

            $categories
            ,
            [
                "search" => "البحث بالاسم او المحتوي"
            ]
        );
    }
}
