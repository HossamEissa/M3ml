<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\VerificationServices;
use App\Traits\responseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }


}
