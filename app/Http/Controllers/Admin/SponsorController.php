<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\SaveImageTrait;
use App\DeleteImageTrait;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Validator;

class SponsorController extends Controller
{
    use HandleResponseTrait, SaveImageTrait, DeleteImageTrait;

    public function index() {
        return view('Admin.sponsors.index');
    }

    public function get() {
        $Sponsors = Sponsor::all();

        return $this->handleResponse(
            true,
            "",
            [],
            [
                $Sponsors
            ],
            []
        );
    }

    public function add() {
        return view("Admin.sponsors.create");
    }

    public function edit($id) {
        $sponsor = Sponsor::find($id);

        if ($sponsor)
            return view("Admin.sponsors.edit")->with(compact("sponsor"));

        return $this->handleResponse(
            false,
            "Sponsor not exits",
            ["Sponsor id not valid"],
            [],
            []
        );
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => ["required"],
            "link" => ["required"],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "name.required" => "ادخل اسم الراعي",
            "link.required" => "ادخل رابط الراعي",
            "image.image" => "من فضلك ارفع صورة صالحة",
            "image.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "image.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
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

        $image = $this->saveImg($request->image, 'images/uploads/Sponsors', time());


        $sponsor = Sponsor::create([
            "name" => $request->name,
            "link" => $request->link,
            "image_path" => '/images/uploads/Sponsors/' . $image,
        ]);

        if ($sponsor)
            return $this->handleResponse(
                true,
                "تم اضافة الراعي بنجاح",
                [],
                [],
                []
            );

    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => ["required"],
            "name" => ["required"],
            "link" => ["required"],
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            "name.required" => "ادخل اسم الراعي",
            "link.required" => "ادخل رابط الراعي",
            "image.image" => "من فضلك ارفع صورة صالحة",
            "image.mimes" => "يجب ان تكون الصورة بين هذه الصيغ (jpeg, png, jpg, gif)",
            "image.max" => "يجب الا يتعدى حجم الصورة 2 ميجا",
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

        $sponsor = Sponsor::find($request->id);

        if ($request->image) {
            $this->deleteFile(base_path($sponsor->image_path));
            $image = $this->saveImg($request->image, 'images/uploads/Sponsors', time());
            $sponsor->image_path= '/images/uploads/Sponsors/' . $image;
        }

        $sponsor->name = $request->name;
        $sponsor->link = $request->link;
        $sponsor->save();

        if ($sponsor)
            return $this->handleResponse(
                true,
                "تم تحديث البيانات بنجاح",
                [],
                [],
                []
            );

    }

    public function deleteIndex($id) {
        $sponsor = Sponsor::find($id);

        if ($sponsor)
            return view("Admin.sponsors.delete")->with(compact("sponsor"));

        return $this->handleResponse(
            false,
            "sponsor not exits",
            ["sponsor id not valid"],
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

        $Sponsor = Sponsor::find($request->id);

        $this->deleteFile(base_path($Sponsor->image_path));

        $Sponsor->delete();

        if ($Sponsor)
            return $this->handleResponse(
                true,
                "تم حذف الراعي بنجاح",
                [],
                [],
                []
            );

    }

    public function toggleTop($id) {
        $sponsor = Sponsor::find($id);
        if ($sponsor) {
            $sponsor->isTop = !$sponsor->isTop;
            $sponsor->save();
        }

        return redirect()->back();
    }

}
