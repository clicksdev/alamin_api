<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Event;

class EventController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $events = Event::latest()
                       ->select("id", "title", "sub_title", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                       ->with(['relatedEvents' => function($query) {
                        $query->select("id", "title", "sub_title", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                              ->where('date_to', '>=', now()); // Ensure active events only
                        }, "location"])
                       ->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $events
            ],
            [
                "يبدا مسار الصورة من بعد الدومين مباشرا"
            ]
        );
    }

    public function search(Request $request) {
        $search = $request->search ? $request->search : '';
        $events = Event::latest()
                       ->select("id", "title", "sub_title", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                       ->where('title', 'like', '%' . $search . '%')
                       ->orWhere('sub_title', 'like', '%' . $search . '%')
                       ->with(['relatedEvents' => function($query) {
                           $query->select("id", "title", "sub_title", "cover", "thumbnail", "landscape", "portrait", "url", "date_from", "date_to", "location_id")
                                 ->where('id', '!=', $query->getModel()->id);
                       }])
                       ->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $events
            ],
            [
                "search" => "البحث بالعنوان او العنوان الفرعي"
            ]
        );
    }
}
