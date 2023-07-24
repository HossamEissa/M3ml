<?php

namespace App\Http\Controllers;

use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use function PHPUnit\Framework\isFalse;

class LoginController extends Controller
{
    use responseTrait;
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $valid = Validator::make($request->all(), [
                'phone_number' => 'required|numeric|digits:11',
                'password' => 'required|string',
            ]);

            if ($valid->fails()) {
                return $this->returnValidationError($valid);
            }

            $credentials = $request->only('phone_number', 'password');
            if (!auth::attempt($credentials)) {
                $msg = "رقم الهاتف او الرقم السرى غير صحيح ";
                return $this->returnError('000', $msg);
            }


            $user = Auth::user();
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



}
