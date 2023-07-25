<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Services\VerificationServices;
use App\Models\User;
use App\Models\Verification_code;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    use responseTrait;

    public $services;

    public function __construct(VerificationServices $services)
    {
        $this->services = $services;
    }

    public function check(ForgetPasswordRequest $request)
    {
        $user = User::where('phone_number', $request->phone)->first();
        Verification_code::where('user_id', $user->id)->delete();
        if ($user) {
            SMS_make($user->id, $this->services);
            $data['user_id'] = $user->id;
            return $this->returnData('data', $data, 'تم العثور علي الحساب');
        } else {
            return $this->returnError('', 'هذا الهاتف غير مسجل لدينا');
        }
    }
}
