<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\SaveImageTrait;
use App\DeleteImageTrait;
use App\Models\Ad;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    use HandleResponseTrait, SaveImageTrait, DeleteImageTrait;

    public function index() {
        return view('Admin.ads.index');
    }

    public function get() {
        $ads = Ad::all();

        return $this->handleResponse(
            true,
            "",
            [],
            [
                $ads
            ],
            []
        );
    }

    public function add() {
        return view("Admin.ads.create");
    }

    public function edit($id) {
        $ad = Ad::latest()->find($id);

        if ($ad)
            return view("Admin.ads.edit")->with(compact("ad"));

        return $this->handleResponse(
            false,
            "Ad not exits",
            ["Ad id not valid"],
            [],
            []
        );
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            "title" => ["required"],
            "title_ar" => ["required"],
            "link" => ["required"],
            "description" => ["required"],
            "description_ar" => ["required"],
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "name.required" => "ادخل اسم الاعلان",
            "name.max" => "يجب الا يتعدى اسم الاعلان 100 حرف",
            "description.required" => "ادخل وصف الاعلان",
            "photo.required" => "الصورة المصغرة للاعلان مطلوبة",
            "photo.image" => "من فضلك ارفع صورة صالحة",
            "photo.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "photo.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
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

        $image = $this->saveImg($request->photo, 'images/uploads/Ads', time());

        $ad = Ad::create([
            "title" => $request->title,
            "description" => $request->description,
            "title_ar" => $request->title_ar,
            "description_ar" => $request->description_ar,
            "link" => $request->link,
            "photo_path" => '/images/uploads/Ads/' . $image,
        ]);

        if ($ad)
            return $this->handleResponse(
                true,
                "تم اضافة الاعلان بنجاح",
                [],
                [],
                []
            );

    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => ["required"],
            "title_ar" => ["required"],
            "title" => ["required"],
            "link" => ["required"],
            "description" => ["required"],
            "description_ar" => ["required"],
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "name.required" => "ادخل اسم الاعلان",
            "name.max" => "يجب الا يتعدى اسم الاعلان 100 حرف",
            "description.required" => "ادخل وصف الاعلان",
            "photo.required" => "الصورة المصغرة للاعلان مطلوبة",
            "photo.image" => "من فضلك ارفع صورة صالحة",
            "photo.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "photo.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
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

        $ad = Ad::find($request->id);

        if ($request->photo) {
            $this->deleteFile(base_path($ad->photo_path));
            $image = $this->saveImg($request->photo, 'images/uploads/Ads', time());
            $ad->photo_path= '/images/uploads/Ads/' . $image;
        }


        $ad->title = $request->title;
        $ad->link = $request->link;
        $ad->description = $request->description;
        $ad->title_ar = $request->title_ar;
        $ad->description_ar = $request->description_ar;
        $ad->save();

        if ($ad)
            return $this->handleResponse(
                true,
                "تم تحديث الاعلان بنجاح",
                [],
                [],
                []
            );

    }

    public function deleteIndex($id) {
        $ad = Ad::find($id);

        if ($ad)
            return view("Admin.ads.delete")->with(compact("ad"));

        return $this->handleResponse(
            false,
            "Ad not exits",
            ["Ad id not valid"],
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

        $ad = Ad::find($request->id);

        $this->deleteFile(base_path($ad->photo_path));

        $ad->delete();

        if ($ad)
            return $this->handleResponse(
                true,
                "تم حذف الاعلان بنجاح",
                [],
                [],
                []
            );

    }
}
