<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\SaveImageTrait;
use App\DeleteImageTrait;
use App\Models\Location;
use App\Models\Product;
use App\Models\Gallery;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    use HandleResponseTrait, SaveImageTrait, DeleteImageTrait;

    public function index() {
        return view('Admin.locations.index');
    }

    public function get() {
        $locations = Location::all();

        return $this->handleResponse(
            true,
            "",
            [],
            [
                $locations
            ],
            []
        );
    }

    public function add() {
        return view("Admin.locations.create");
    }

    public function edit($id) {
        $location = Location::latest()->find($id);

        if ($location)
            return view("Admin.locations.edit")->with(compact("location"));

        return $this->handleResponse(
            false,
            "Location not exits",
            ["Categry id not valid"],
            [],
            []
        );
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            "title" => ["required", "max:100"],
            "sub_title" => ["required"],
            "sub_title_ar" => ["required"],
            "title_ar" => ["required", "max:100"],
            "url" => ["required"],
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "name.required" => "ادخل اسم الموقع",
            "name.max" => "يجب الا يتعدى اسم الموقع 100 حرف",
            "sub_title.required" => "ادخل عنوان ثانوي الموقع",
            "url.required" => "ادخل رابط الموقع ع الخريطة",
            "thumbnail.required" => "الصورة المصغرة للموقع مطلوبة",
            "thumbnail.image" => "من فضلك ارفع صورة صالحة",
            "thumbnail.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "thumbnail.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
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

        $image = $this->saveImg($request->thumbnail, 'images/uploads/Locations', time());
        $cover = $this->saveImg($request->cover, 'images/uploads/Locations', time());

        $location = Location::create([
            "title" => $request->title,
            "sub_title" => $request->sub_title,
            "title_ar" => $request->title_ar,
            "sub_title_ar" => $request->sub_title_ar,
            "url" => $request->url,
            "cover_path" => '/images/uploads/Locations/' . $cover,
            "thumbnail_path" => '/images/uploads/Locations/' . $image,
        ]);

        if ($location)
            return $this->handleResponse(
                true,
                "تم اضافة الموقع بنجاح",
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
            "title_ar" => ["required", "max:100"],
            "sub_title_ar" => ["required"],
            'cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "name.required" => "ادخل اسم الموقع",
            "name.max" => "يجب الا يتعدى اسم الموقع 100 حرف",
            "sub_title.required" => "ادخل عنوان ثانوي الموقع",
            "thumbnail.required" => "الصورة المصغرة للموقع مطلوبة",
            "thumbnail.image" => "من فضلك ارفع صورة صالحة",
            "thumbnail.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "thumbnail.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
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

        $location = Location::find($request->id);

        if ($request->thumbnail) {
            $this->deleteFile(base_path($location->thumbnail_path));
            $image = $this->saveImg($request->thumbnail, 'images/uploads/Locations', time());
            $location->thumbnail_path= '/images/uploads/Locations/' . $image;
        }

        if ($request->cover) {
            $this->deleteFile(base_path($location->cover_path));
            $image = $this->saveImg($request->cover, 'images/uploads/Locations', time());
            $location->thumbnail_path= '/images/uploads/Locations/' . $image;
        }

        $location->title = $request->title;
        $location->sub_title = $request->sub_title;
        $location->title_ar = $request->title_ar;
        $location->sub_title_ar = $request->sub_title_ar;
        $location->url = $request->url;
        $location->save();

        if ($location)
            return $this->handleResponse(
                true,
                "تم تحديث الموقع بنجاح",
                [],
                [],
                []
            );

    }

    public function deleteIndex($id) {
        $location = Location::find($id);

        if ($location)
            return view("Admin.locations.delete")->with(compact("location"));

        return $this->handleResponse(
            false,
            "Location not exits",
            ["Categry id not valid"],
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

        $location = Location::find($request->id);

        $this->deleteFile(base_path($location->thumbnail_path));

        $location->delete();

        if ($location)
            return $this->handleResponse(
                true,
                "تم حذف الموقع بنجاح",
                [],
                [],
                []
            );

    }

}
