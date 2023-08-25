<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Services\VerificationServices;
use App\Models\User;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
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
    public function register(RegisterUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create(request()->all());
            $user->password = Hash::make(request('password'));
            $user->save();
            if ($request->hasFile('photo')) {
                if ($user->photo != '0') {
                    delete_image('users', $user->photo);
                }
                $image_path = upload_image($request, 'profile', 'photo', 'users');
                $user->photo = $image_path;
                $user->save();
            }

            SMS_make($user, $this->verificationServices);

            DB::commit();
            $msg = "تم تسجيل الحساب بنجاح";
            return $this->returnSuccessMessage($msg);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg['msg'] = "حدث خطأ ما حاول مجددا فى وقت لاحق ";
            $msg['error'] = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }

    }

}
