<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\changepasswordsuper;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassword
{
    use responseTrait;

    public function __construct()
    {

    }

    public function change_password(ChangePasswordRequest $request){
        try {

            $admin = Auth('admin')->user();

            $old_password = $request->old_password;

            if(!password_verify($old_password , $admin->password))
            {
                return $this->returnError('' , 'الرقم السرى الذى ادخلته غير صحيح');
            }

            $admin->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->returnSuccessMessage('تم تغير الرقم السرى بنجاح');

        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function change_password_super(changepasswordsuper $request){
        try {

            $admin = Auth('admin')->user();

            $admin->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->returnSuccessMessage('تم تغير الرقم السرى بنجاح');

        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }
}
