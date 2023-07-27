<?php

use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationCodeController;
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


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('verify-user', [VerificationCodeController::class, 'verify']);

#################################### Reset Password #########################################################
Route::post('forget-password', [ForgetPasswordController::class, 'check']);
Route::post('check-reset-password', [VerificationCodeController::class, 'resetPasswordCodeVerify']);
Route::post('reset-password', [ResetPasswordController::class, 'change_password']);
##################################### End Reset Password #######################################################


Route::group(['middleware' => ['verifiedUser:api', 'CheckJwtAuth:api']], function () {
    Route::get('/logout', [AuthUserController::class, 'logout'])->name('logout');
});



