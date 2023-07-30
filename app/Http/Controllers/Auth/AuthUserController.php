<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserProfileRequest;
use App\Http\Services\VerificationServices;
use App\Models\User;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AuthUserController extends Controller
{
    use responseTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public function __construct()
    {
        //$this->middleware('CheckJwtAuth:api', ['except' => ['login', 'register']]);

    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function Edit_Profile(EditUserProfileRequest $request)
    {
        try {
            $user = User::find($request->id);
            $user->update([
                'name' => $request->name ?? $user->name,
                'phone_number' => $request->phone_number ?? $user->phone_number,
                'date_of_birth' => $request->date_of_birth ?? $user->date_of_birth,
                'gender' => $request->gender ?? $user->gender,
            ]);
            if ($request->password) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
            if ($request->hasFile('photo')) {
                if ($user->photo != '0') {
                    delete_image('users', $user->photo);
                }
                $image_path = upload_image($request, 'profile', 'photo', 'users');
                $user->photo = $image_path;
                $user->save();
            }
            $data = $user ;
            $data->photo = Storage::disk('users')->url($user->photo);
            $msg = "تم تعديل الحساب بنجاح";
            return $this->returnData('data' , $user ,$msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }


}
