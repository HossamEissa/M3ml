<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoginAdminRequest;
use App\Models\Factory;
use App\Rules\NoSpaces;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginAdmin
{
    use responseTrait;

    public function __construct()
    {

    }

    public function login(LoginAdminRequest $request)
    {
        try {
            if ($request->name) {
                $factory_active = Factory::select('active')->where('name', $request->name)->first();
                if (!$factory_active) {
                    return $this->returnError('', 'يجب تفعيل المعمل اولا وسداد الاشتراك');
                }
            }
            $credentials = $request->only('name', 'password');
            if (!auth::guard('admin')->attempt($credentials)) {
                $msg = "اسم المعمل او الرقم السرى غير صحيح ";
                return $this->returnError('000', $msg);
            }


            $admin = Auth::guard('admin')->user();
            $token = JWTAuth::fromUser($admin);
            $msg = "تم تسجيل الدخول بنجاح";
            $admin['active'] = $factory_active;
            $data = get_data_of_admin($admin, $token);
            return $this->returnData("data", $data, $msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

}
