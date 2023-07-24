<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Verification_code;
use Illuminate\Support\Facades\Auth;

class VerificationServices
{

    public function setVerificationCode($data)
    {
        $code = mt_rand(100000, 999999);
        $data['code'] = $code;
        Verification_code::whereNotNull('user_id')->where(['user_id' => $data['user_id']])->delete();
        return Verification_code::create($data);
    }

    public function getSMSVerifyMessage($code)
    {
        $message = "This is your verification Code from Hossam Eissa dont't share it ";
        return $message.$code;
    }

    public function checkOtpCode($code)
    {
        if (Auth::user()->mobile_verified_at == null) {
            $verification_data = Verification_code::where('user_id', Auth::id())->first();
            if ($verification_data->code == $code) {
                User::whereId(Auth::id())->update([
                    'mobile_verified_at' => now()
                ]);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function removeOtpCode($code)
    {
        Verification_code::where('code', $code)->delete();
    }

}
