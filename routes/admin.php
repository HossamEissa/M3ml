<?php

use App\Http\Controllers\Admin\ChangePassword;
use App\Http\Controllers\Admin\LoginAdmin;
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
################################ End Auth ##############################################

################################ Factory ###############################################
Route::post('create-factory', [RegisterForNewAdmin::class, 'create']);
Route::post('change-password', [ChangePassword::class, 'change_password']);
Route::post('change-password-super-admin', [ChangePassword::class, 'change_password_super']);
Route::get('all-users', [FactoryController::class, 'allUsers']);
Route::post('edit-factory', [FactoryController::class, 'edit']);
Route::get('all-m3ml', [FactoryController::class, 'all_m3ml']);

################################ End Factory ###########################################

################################# Document #############################################

Route::group(['middleware' => ['CheckJwtAuth:admin']], function () {
    Route::post('add-document', [DocumentController::class, 'add']);
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
