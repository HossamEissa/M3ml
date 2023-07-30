<?php

use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\FactoryOfferController;
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


#################################### Auth ##########################################################
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('verify-user', [VerificationCodeController::class, 'verify']);
Route::group(['middleware' => ['verifiedUser:api', 'CheckJwtAuth:api']], function () {
    Route::get('/logout', [AuthUserController::class, 'logout'])->name('logout');
});
Route::post('edit_profile', [AuthUserController::class, 'Edit_Profile']);
#################################### End Auth ########################################################

#################################### Reset Password ######################################################
Route::post('forget-password', [ForgetPasswordController::class, 'check']);
Route::post('check-reset-password', [VerificationCodeController::class, 'resetPasswordCodeVerify']);
Route::post('reset-password', [ResetPasswordController::class, 'change_password']);
##################################### End Reset Password ##################################################

################################ Factory Information ###################################################
Route::group(['middleware' => ['CheckJwtAuth:api', 'verifiedUser:api']], function () {

    Route::post('find-factory', [FactoryController::class, 'find']);
    Route::post('show-document', [DocumentController::class, 'show']);
    Route::post('download-document', [DocumentController::class, 'download']);

});
################################ End Factory Information ################################################

################################ Offers ################################################################
Route::group(['middleware' => ['CheckJwtAuth:api', 'verifiedUser:api']], function () {
    Route::post('show-offers', [FactoryOfferController::class, 'show']);
});
################################ End Offers ############################################################

