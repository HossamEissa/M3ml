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
        $this->middleware('CheckJwtAuth:api');

    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function Edit_Profile(EditUserProfileRequest $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->update([
                'name' => $request->name ?? $user->name,
                'date_of_birth' => $request->date_of_birth ?? $user->date_of_birth,
                'gender' => $request->gender ?? $user->gender,
            ]);
            $newPhoneNumber = $request->input('phone_number');
            $currentUser = User::find($user->id);
            if ($currentUser->phone_number !== $newPhoneNumber) {
                $currentUser->phone_number = $newPhoneNumber;
                $currentUser->save();
            }
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
            $msg = "تم تعديل الحساب بنجاح";
            $data = get_data_of_user($user , '');
            return $this->returnData('data', $data, $msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function profile(Request $request){
        $user = Auth::user();
        $data = get_data_of_user($user ,'');
        return $this->returnData('data' , $data );
    }

    public function delete_user(){
        $user = Auth::user();
        $find = User::find($user->id)->delete();
        if ($find)
        return $this->returnSuccessMessage('تم حذف الحساب بنجاح');
        else
            return $this->returnError('' , 'لم يتم حذف الحساب حاول مره اخرى');
    }

}
