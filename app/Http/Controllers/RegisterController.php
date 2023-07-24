<?php

namespace App\Http\Controllers;

use App\Http\Services\VerificationServices;
use App\Models\User;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use responseTrait;
    public $verificationServices;

    public function __construct(VerificationServices $verificationServices)
    {
        $this->verificationServices = $verificationServices;
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {

            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'phone_number' => 'required|numeric|digits:11|unique:users,phone_number',
                'date_of_birth' => 'required|string',
                'gender' => 'required|in:male,female',
                'password' => 'required|confirmed|min:8',
                'photo' => 'mimes:jpg,jpeg,png,gif,svg',
            ]);


            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }
            $user = User::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'photo' => '0',
                'password' => Hash::make($request->password),
            ]);
            if ($request->hasFile('photo')) {
                if ($user->photo != '0') {
                    delete_image('users', $user->photo);
                }
                $image_path = upload_image($request, 'profile', 'photo', 'users');
                $user->photo = $image_path;
                $user->save();
            }

            // SMS OTP to users
            $this->SMS_make($user);
            //END SMS OTP to users

            $msg = "تم تسجيل الحساب بنجاح";
            return $this->returnSuccessMessage($msg);


        } catch (\Exception $e) {
            DB::rollBack();
            $msg = "حاول مجددا فى وقت لاحق ";
            return $this->returnError($error = "", $msg);
        }

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
