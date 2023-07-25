<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordUserRequest;
use App\Http\Requests\ResetPasswordUserRequest;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use responseTrait;

    public function __construct()
    {
        $this->middleware(['verifiedUser:api', 'CheckJwtAuth:api']);
    }


    public function change_password(ChangePasswordUserRequest $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return $this->returnSuccessMessage('تم تغيير الباسورد بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = "حدث خطأ ما حاول مرة اخرى  ";
            return $this->returnError($error = "", $msg);
        }


    }
}
