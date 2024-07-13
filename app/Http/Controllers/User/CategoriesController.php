<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Category;
use Carbon\Carbon;

class CategoriesController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $categories = Category::with(["events" => function($q) {
            $q->where('date_to', '>=', now("GMT+3"))
            ->orderBy("date_from", "asc")
            ->with(['relatedEvents' => function($query) {
                  $query->select("id", "title", "sub_title", "title_ar", "sub_title_ar", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                        ->where('date_to', '>=', now("GMT+3"));
              }, "location"]);
        }])->get();

        foreach ($categories as $cat) {
            foreach ($cat->events as $event) {
                if ($event) {
                    $event->time_from = Carbon::parse($event->date_from)->format('h:i A');
                    $event->time_to = Carbon::parse($event->date_to)->format('h:i A');
                    foreach ($event->relatedEvents as $relatedEvent) {
                        $relatedEvent->time_from = Carbon::parse($relatedEvent->date_from)->format('h:i A');
                        $relatedEvent->time_to = Carbon::parse($relatedEvent->date_to)->format('h:i A');
                    }
                }
            }
        }

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

    public function category(Request $request) {
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
