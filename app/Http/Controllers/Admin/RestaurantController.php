<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\SaveImageTrait;
use App\DeleteImageTrait;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    use HandleResponseTrait, SaveImageTrait, DeleteImageTrait;

    public function index() {
        return view('Admin.restaurants.index');
    }

    public function get() {
        $restaurants = Restaurant::all();

        return $this->handleResponse(
            true,
            "",
            [],
            [
                $restaurants
            ],
            []
        );
    }

    public function add() {
        return view("Admin.restaurants.create");
    }

    public function edit($id) {
        $restaurant = Restaurant::find($id);

        if ($restaurant)
            return view("Admin.restaurants.edit")->with(compact("restaurant"));

        return $this->handleResponse(
            false,
            "Restaurant not exists",
            ["Restaurant id not valid"],
            [],
            []
        );
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            "title" => ["required", "max:100"],
            "sub_title" => ["required"],
            "title_ar" => ["required", "max:100"],
            "sub_title_ar" => ["required"],
            "description" => ["required"],
            "description_ar" => ["required"],
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'working_from' => 'required',
            'working_to' => 'required',
            'location_id' => 'required|exists:locations,id',
        ], [
            "title.required" => "ادخل عنوان المطعم",
            "title.max" => "يجب الا يتعدى عنوان المطعم 100 حرف",
            "sub_title.required" => "ادخل عنوان ثانوي للمطعم",
            "photo.required" => "صورة المطعم مطلوبة",
            "photo.image" => "من فضلك ارفع صورة صالحة",
            "photo.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "photo.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
            "phone.required" => "رقم الهاتف مطلوب",
            "working_from.required" => "وقت بدء العمل مطلوب",
            "working_to.required" => "وقت انتهاء العمل مطلوب",
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

        $photo = $this->saveImg($request->photo, 'images/uploads/Restaurants', time());

        $restaurant = Restaurant::create([
            "title" => $request->title,
            "sub_title" => $request->sub_title,
            "title_ar" => $request->title_ar,
            "description" => $request->description,
            "description_ar" => $request->description_ar,
            "sub_title_ar" => $request->sub_title_ar,
            "photo_path" => '/images/uploads/Restaurants/' . $photo,
            "phone" => $request->phone ?? null,
            "website" => $request->website ?? null,
            "working_from" => $request->working_from,
            "working_to" => $request->working_to,
            "location_id" => $request->location_id,
        ]);

        if ($restaurant)
            return $this->handleResponse(
                true,
                "تم اضافة المطعم بنجاح",
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
            "description" => ["required"],
            "description_ar" => ["required"],
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'working_from' => 'required',
            'working_to' => 'required',
            'location_id' => 'required|exists:locations,id',
        ], [
            "title.required" => "ادخل عنوان المطعم",
            "title.max" => "يجب الا يتعدى عنوان المطعم 100 حرف",
            "sub_title.required" => "ادخل عنوان ثانوي للمطعم",
            "photo.image" => "من فضلك ارفع صورة صالحة",
            "photo.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "photo.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
            "phone.required" => "رقم الهاتف مطلوب",
            "working_from.required" => "وقت بدء العمل مطلوب",
            "working_to.required" => "وقت انتهاء العمل مطلوب",
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

        $restaurant = Restaurant::find($request->id);

        if ($request->photo) {
            $this->deleteFile(public_path($restaurant->photo_path));
            $photo = $this->saveImg($request->photo, 'images/uploads/Restaurants', time());
            $restaurant->photo_path = '/images/uploads/Restaurants/' . $photo;
        }

        $restaurant->title = $request->title;
        $restaurant->sub_title = $request->sub_title;
        $restaurant->title_ar = $request->title_ar;
        $restaurant->sub_title_ar = $request->sub_title_ar;
        $restaurant->phone = $request->phone;
        $restaurant->website = $request->website;
        $restaurant->description = $request->description;
        $restaurant->description_ar = $request->description_ar;
        $restaurant->working_from = $request->working_from;
        $restaurant->working_to = $request->working_to;
        $restaurant->location_id = $request->location_id;
        $restaurant->save();

        if ($restaurant)
            return $this->handleResponse(
                true,
                "تم تحديث المطعم بنجاح",
                [],
                [],
                []
            );
    }

    public function deleteIndex($id) {
        $restaurant = Restaurant::find($id);

        if ($restaurant)
            return view("Admin.restaurants.delete")->with(compact("restaurant"));

        return $this->handleResponse(
            false,
            "Restaurant not exists",
            ["Restaurant id not valid"],
            [],
            []
        );
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => ["required"],
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

        $restaurant = Restaurant::find($request->id);

        $this->deleteFile(public_path($restaurant->photo_path));

        $restaurant->delete();

        if ($restaurant)
            return $this->handleResponse(
                true,
                "تم حذف المطعم بنجاح",
                [],
                [],
                []
            );
    }
}
