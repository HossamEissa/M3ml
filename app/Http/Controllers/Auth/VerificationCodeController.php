<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\ResetPasswordUserRequest;
use App\Http\Requests\VerificationCodeRequest;
use App\Http\Services\VerificationServices;
use App\Models\User;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerificationCodeController extends Controller
{
    use responseTrait;

    public $verificationServices;

    public function __construct(VerificationServices $verificationServices)
    {
        $this->verificationServices = $verificationServices;
    }

    public function verify(VerificationCodeRequest $request)
    {
        $check = $this->verificationServices->checkOtpCode($request->code);
        if ($check) {
            $this->verificationServices->removeOtpCode($request->code);
            return $this->returnSuccessMessage('تم التحقق بنجاح من رقم الهاتف');
        } else {
            return $this->returnError($this->getErrorCode('mobile'), 'يرجى ادخال كود التحقق الصحيح');
        }

    }

    public function resetPasswordCodeVerify(ResetPasswordUserRequest $request)
    {
        $check = $this->verificationServices->checkOtpResetPassword($request->id, $request->code);
        if ($check) {
            $this->verificationServices->removeOtpCode($request->code);
            return app(LoginController::class)->loginAfterReset($request->id);
        } else {
            return $this->returnError($this->getErrorCode('mobile'), 'يرجى ادخال كود التحقق الصحيح');
        }
    }


}
