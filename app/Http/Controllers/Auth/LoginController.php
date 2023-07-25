<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    use responseTrait;

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        try {

            $credentials = $request->only('phone_number', 'password');
            if (!auth::guard('api')->attempt($credentials)) {
                $msg = "رقم الهاتف او الرقم السرى غير صحيح ";
                return $this->returnError('000', $msg);
            }


            $user = Auth::guard('api')->user();
            $token = JWTAuth::fromUser($user);
            $msg = "تم تسجيل الدخول بنجاح";
            $data = get_data_of_user($user, $token);
            $data['verified'] = ($user->mobile_verified_at == null) ? false : true;
            return $this->returnData("data", $data, $msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function loginAfterReset($id)
    {
        $user = Auth::loginUsingId($id);
        $token = JWTAuth::fromUser($user);
        $data = get_data_of_user($user, $token);
        $data['verified'] = ($user->mobile_verified_at == null) ? false : true;
        return $this->returnData("data", $data, 'تم التحقق من رقم الموبايل بنجاح');
    }


}
