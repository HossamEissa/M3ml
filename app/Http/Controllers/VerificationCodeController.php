<?php

namespace App\Http\Controllers;

use App\Http\Services\VerificationServices;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerificationCodeController extends Controller
{
    use responseTrait;

    public $verificationServices;

    public function __construct(VerificationServices $verificationServices)
    {
        $this->verificationServices = $verificationServices;
    }

    public function verify(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required',
        ], ['code.required' => 'هذا الحقل مطلوب من فضلك ادخل الكود']);

        if ($validation->fails()) {
            return $this->returnValidationError($validation);
        }

        $check = $this->verificationServices->checkOtpCode($request->code);
        if ($check) {
            $this->verificationServices->removeOtpCode($request->code);
            return $this->returnSuccessMessage('تم التحقق بنجاح من رقم الهاتف');
        } else {
            return $this->returnError($this->getErrorCode('mobile'), 'يرجى ادخال كود التحقق الصحيح');
        }

    }
}
