<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordUserRequest;
use App\Http\Requests\ResetPasswordUserRequest;
use App\Models\User;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use responseTrait;

    public function __construct()
    {
    }


    public function change_password(ChangePasswordUserRequest $request)
    {
        try {
            $user = User::where('phone_number', $request->mobile)->first();
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            $msg = 'تم تغير الرقم السرى بنجاح';
            return app(LoginController::class)->loginAfterReset($user->id, $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = "حدث خطأ ما حاول مرة اخرى  ";
            return $this->returnError($error = "", $msg);
        }


    }
}
