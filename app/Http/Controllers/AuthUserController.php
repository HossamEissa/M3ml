<?php

namespace App\Http\Controllers;

use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Tymon\JWTAuth\Facades\JWTAuth;
use function PHPUnit\Framework\isNull;


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
        $this->middleware('CheckJwtAuth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'phone_number' => 'required|numeric|digits:11',
                'date_of_birth' => 'required|string|',
                'gender' => 'required|in:male,female',
                'password' => 'required|confirmed|min:8',
            ]);


            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }
            User::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
            ]);
            $msg = "Register Successfully ! ";
            return $this->returnSuccessMessage($msg);

        } catch (\Exception $e) {

        }

    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $valid = Validator::make($request->all(), [
                'phone_number' => 'required|numeric|digits:11',
                'password' => 'required|string',
            ]);

            if ($valid->fails()) {
                return $this->returnValidationError($valid);
            }

            $credentials = $request->only('phone_number', 'password');
            if (!auth::attempt($credentials)) {
                $msg = "Your Phone number or password are not correct ";
                return $this->returnError('000', $msg);
            }


            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            $msg = "Login Successfully ";
            $data = get_data_of_user($user, $token);
            return $this->returnData("data", $data, $msg);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }

    public function set_image(Request $request)
    {
        try {
            $valid = Validator::make($request->all(), [
                'photo' => 'mimes:jpg,jpeg,png,gif,svg,pdf',
            ]);
            if ($valid->fails()) {
                return $this->returnValidationError($valid);
            }
            if ($request->hasFile('photo')){
                $user = Auth::user();
                if ($user->photo != '0'){
                 delete_image('users' , $user->photo);
                }
                $image_path = upload_image($request, 'profile', 'photo', 'users');
                $user->photo = $image_path;
                $user->save();
                $token = JWTAuth::fromUser($user);
                $data = get_data_of_user($user, $token);
                return $this->returnData("data", $data);

            }else{
                return $this->returnError('' , "No image found");
            }

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $this->returnError($error = "", $msg);
        }
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


}
