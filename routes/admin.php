<?php

use App\Http\Controllers\Admin\LoginAdmin;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\SuperAdmin\RegisterForNewAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', [RegisterForNewAdmin::class, 'register']);
Route::post('login', [LoginAdmin::class, 'login']);
