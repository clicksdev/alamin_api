<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\SaveImageTrait;
use App\DeleteImageTrait;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventController extends Controller
{
    use HandleResponseTrait, SaveImageTrait, DeleteImageTrait;

    public function index() {
        return view('Admin.events.index');
    }

    public function get() {
        $events = Event::all();

        return $this->handleResponse(
            true,
            "",
            [],
            [
                $events
            ],
            []
        );
    }

    public function add() {
        return view("Admin.events.create");
    }

    public function edit($id) {
        $event = Event::with("event_categories")->find($id);

        if ($event)
            return view("Admin.events.edit")->with(compact("event"));

        return $this->handleResponse(
            false,
            "Event not exists",
            ["Event id not valid"],
            [],
            []
        );
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            "title" => ["required", "max:100"],
            "sub_title" => ["required"],
            "categories" => ["required"],
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'landscape' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'portrait' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "title.required" => "ادخل عنوان الحدث",
            "title.max" => "يجب الا يتعدى عنوان الحدث 100 حرف",
            "sub_title.required" => "ادخل عنوان ثانوي للحدث",
            "url.required" => "ادخل رابط الحدث",
            "thumbnail.required" => "الصورة المصغرة للحدث مطلوبة",
            "thumbnail.image" => "من فضلك ارفع صورة صالحة",
            "thumbnail.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "thumbnail.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
            "cover.required" => "صورة الغلاف للحدث مطلوبة",
            "cover.image" => "من فضلك ارفع صورة صالحة",
            "cover.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "cover.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }

        $thumbnail = $this->saveImg($request->thumbnail, 'images/uploads/Events', time());
        $cover = $this->saveImg($request->cover, 'images/uploads/Events', time());
        $landscape = $this->saveImg($request->landscape, 'images/uploads/Events', time());
        $portrait = $this->saveImg($request->portrait, 'images/uploads/Events', time());

        $event = Event::create([
            "title" => $request->title,
            "sub_title" => $request->sub_title,
            "url" => $request->url ?? null,
            "thumbnail" => '/images/uploads/Events/' . $thumbnail,
            "cover" => '/images/uploads/Events/' . $cover,
            "portrait" => '/images/uploads/Events/' . $portrait,
            "landscape" => '/images/uploads/Events/' . $landscape,
            "categories" => json_encode($request->categories),
            "date_from" => Carbon::parse($request->date_from),
            "date_to" => Carbon::parse($request->date_to),
            "location_id" => $request->location_id,
        ]);

        $event->event_categories()->attach(json_decode($request->categories));


        if ($event)
            return $this->handleResponse(
                true,
                "تم اضافة الحدث بنجاح",
                [],
                [],
                []
            );

    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => ["required"],
            "title" => ["required", "max:100"],
            "sub_title" => ["required"],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'location_id' => 'required|exists:locations,id',
            'cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'landscape' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'portrait' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "title.required" => "ادخل عنوان الحدث",
            "title.max" => "يجب الا يتعدى عنوان الحدث 100 حرف",
            "sub_title.required" => "ادخل عنوان ثانوي للحدث",
            "thumbnail.image" => "من فضلك ارفع صورة صالحة",
            "thumbnail.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "thumbnail.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
            "cover.image" => "من فضلك ارفع صورة صالحة",
            "cover.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "cover.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }

        $event = Event::find($request->id);

        if ($request->thumbnail) {
            $this->deleteFile(public_path($event->thumbnail));
            $thumbnail = $this->saveImg($request->thumbnail, 'images/uploads/Events', time());
            $event->thumbnail = '/images/uploads/Events/' . $thumbnail;
        }

        if ($request->cover) {
            $this->deleteFile(public_path($event->cover));
            $cover = $this->saveImg($request->cover, 'images/uploads/Events', time());
            $event->cover = '/images/uploads/Events/' . $cover;
        }

        if ($request->landscape) {
            $this->deleteFile(public_path($event->landscape));
            $landscape = $this->saveImg($request->landscape, 'images/uploads/Events', time());
            $event->landscape = '/images/uploads/Events/' . $landscape;
        }

        if ($request->portrait) {
            $this->deleteFile(public_path($event->portrait));
            $portrait = $this->saveImg($request->portrait, 'images/uploads/Events', time());
            $event->portrait = '/images/uploads/Events/' . $portrait;
        }

        $event->title = $request->title;
        $event->sub_title = $request->sub_title;
        $event->url = $request->url ?? null;
        $event->date_from = Carbon::parse($request->date_from);
        $event->date_to = Carbon::parse($request->date_to);
        $event->location_id = $request->location_id;
        $event->categories = json_encode($request->categories);
        $event->event_categories()->detach();
        $event->event_categories()->attach(json_decode($request->categories));
        $event->save();

        if ($event)
            return $this->handleResponse(
                true,
                "تم تحديث الحدث بنجاح",
                [],
                [],
                []
            );

    }

    public function deleteIndex($id) {
        $event = Event::find($id);

        if ($event)
            return view("Admin.events.delete")->with(compact("event"));

        return $this->handleResponse(
            false,
            "Event not exists",
            ["Event id not valid"],
            [],
            []
        );
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => ["required"],
        ], [
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }

        $event = Event::find($request->id);

        $this->deleteFile(public_path($event->thumbnail));
        $this->deleteFile(public_path($event->cover));
        $this->deleteFile(public_path($event->portrait));
        $this->deleteFile(public_path($event->landscape));

        $event->delete();

        if ($event)
            return $this->handleResponse(
                true,
                "تم حذف الحدث بنجاح",
                [],
                [],
                []
            );

    }
}
