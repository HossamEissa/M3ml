<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoginAdminRequest;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginAdmin
{
    use responseTrait;

    public function login(LoginAdminRequest $request)
    {
        try {

            $credentials = $request->only('name', 'password');
            if (!auth::guard('admin')->attempt($credentials)) {
                $msg = "رقم الهاتف او الرقم السرى غير صحيح ";
                return $this->returnError('000', $msg);
            }


            $admin = Auth::guard('admin')->user();
            $token = JWTAuth::fromUser($admin);
            $msg = "تم تسجيل الدخول بنجاح";
            $data = get_data_of_admin($admin, $token);
            return $this->returnData("data", $data, $msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }
}
