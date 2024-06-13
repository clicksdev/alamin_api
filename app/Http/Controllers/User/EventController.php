<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Event;
use App\Models\Topevent;
use App\Models\Ad;

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
                "search" => "البحث بالعنوان او العنوان الفرعي"
            ]
        );
    }

    public function getTop() {
        $events = Topevent::orderBy("sort", "asc")->get();

        if ($events->count() > 0) {
            foreach ($events as $item) {
                $itemObj = $item->type == 1 ? Event::find($item->item_id) : Ad::find($item->item_id);
                if ($itemObj) {
                    $itemObj->type = $item->type == 1 ? "Event" : "Ad";
                    $item->item = $itemObj;
                }
            }
        }
        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $events
            ],
            [
                "type" => [
                    1 => "Event",
                    2 => "Ad"
                ]
            ]
        );
    }
}
