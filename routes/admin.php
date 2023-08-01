<?php

use App\Http\Controllers\Admin\LoginAdmin;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\FactoryOfferController;
use App\Http\Controllers\SuperAdmin\ActivationController;
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

################################## Auth ###############################################
Route::post('register', [RegisterForNewAdmin::class, 'register']);
Route::post('login', [LoginAdmin::class, 'login']);
Route::post('activation', [ActivationController::class, 'activation']);
Route::post('change-admin-password', [ActivationController::class, 'change_password']);
################################ End Auth ##############################################

################################ Factory ###############################################
Route::post('create-factory', [RegisterForNewAdmin::class, 'create']);
    Route::get('all-users', [FactoryController::class, 'allUsers']);
Route::group(['middleware' => 'CheckJwtAuth:admin'], function () {
    Route::post('edit-factory', [FactoryController::class, 'edit']);
    Route::post('change-password', [FactoryController::class, 'change_password']);
});
################################ End Factory ###########################################

################################# Document #############################################
Route::post('add-document', [DocumentController::class, 'add']);
Route::group(['middleware' => ['CheckJwtAuth:admin']], function () {
    Route::post('show-document', [DocumentController::class, 'show']);
    Route::post('delete-document', [DocumentController::class, 'delete']);
});
################################# End Document ##########################################

################################# Offers ################################################
Route::group(['middleware' => 'CheckJwtAuth:admin'], function () {
    Route::post('add-offer', [FactoryOfferController::class, 'add']);
    Route::post('show-offer', [FactoryOfferController::class, 'show']);
    Route::post('delete-offer', [FactoryOfferController::class, 'delete']);
    Route::post('edit-offer', [FactoryOfferController::class, 'edit']);
});
################################# End Offers #############################################
