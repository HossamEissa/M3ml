<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Verification_code;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\Auth;

class VerificationServices
{
    use  responseTrait;

    public function setVerificationCode($data)
    {
        $code = mt_rand(100000, 999999);
        $data['code'] = $code;
        Verification_code::whereNotNull('user_id')->where(['user_id' => $data['user_id']])->delete();
        return Verification_code::create($data);
    }

    public function getSMSVerifyMessage($code)
    {
        $message = "هذا كود التحقق الخاص بك  ";
        return $code .''. $message;
    }

    public function checkOtpCode($mobile, $code)
    {
        $user = User::where('phone_number', $mobile)->first();
        if ($user && $user->mobile_verified_at == null) {
            $verification_data = Verification_code::where('user_id', $user->id)->first();
            if ($verification_data && $verification_data->code == $code) {
                User::whereId($user->id)->update([
                    'mobile_verified_at' => now()
                ]);
                return $user->id;
            } else {
                return false;
            }
        }
        return false;
    }

    public function removeOtpCode($code)
    {
        $code_correct = Verification_code::where('code', $code)->delete();
        if (!$code_correct) {
            return $this->returnError('', 'ادخل الكود الصحيح او اعد المحاولة ');
        }
    }

    public function checkOtpResetPassword($user_id, $code)
    {

        $verification_data = Verification_code::where('user_id', $user_id)->first();
        if ($verification_data && $verification_data->code == $code) {
            User::whereId($user_id)->update([
                'mobile_verified_at' => now()
            ]);
            return true;
        } else {
            return false;
        }


    }

}
