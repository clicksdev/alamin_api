<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\SaveImageTrait;
use App\DeleteImageTrait;
use App\Models\Newsemail;
use Illuminate\Support\Facades\Validator;
use App\Http\Exports\ApplicantsExport;
use Maatwebsite\Excel\Facades\Excel;

class SubEmailController extends Controller
{
    use HandleResponseTrait, SaveImageTrait, DeleteImageTrait;

    public function subscribe(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
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

        $email = Newsemail::create([
            "email" => $request->email,
        ]);

        if ($email)
            return $this->handleResponse(
                true,
                "تم التسجيل بنجاح",
                [],
                [],
                []
            );
    }
    public function export(Request $request)
    {
        $users = Newsemail::query();
        return Excel::download(new ApplicantsExport($users), 'emails.xlsx');
    }

}
