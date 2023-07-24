<?php

namespace App\Http\Controllers;

use App\Http\Services\SMSGateways\VictoryLinkSMS;
use App\Http\Services\VerificationServices;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Tymon\JWTAuth\Facades\JWTAuth;
use function PHPUnit\Framework\isNull;


class AuthUserController extends Controller
{
    use responseTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public $verificationServices;

    public function __construct(VerificationServices $verificationServices)
    {
        $this->middleware('CheckJwtAuth:api', ['except' => ['login', 'register']]);
        $this->verificationServices = $verificationServices;
    }




    public function set_image(Request $request)
    {
        try {

            if ($request->hasFile('photo')) {
                $user = Auth::user();
                if ($user->photo != '0') {
                    delete_image('users', $user->photo);
                }
                $image_path = upload_image($request, 'profile', 'photo', 'users');
                $user->photo = $image_path;
                $user->save();
            }

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }


    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function SMS_make($user)
    {
        $verification = [];
        $sms_services = $this->verificationServices;
        $verification['user_id'] = $user->id;
        $verification_data = $sms_services->setVerificationCode($verification);
        $message = $sms_services->getSMSVerifyMessage($verification_data->code);
        // app(VictoryLinkSMS::class)->sendSms($user->phone_number, $message);
    }

}
