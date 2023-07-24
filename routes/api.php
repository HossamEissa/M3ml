<?php

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerificationCodeController;
use Illuminate\Http\Request;
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


Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['verifiedUser', 'CheckJwtAuth:api'] ], function () {
    Route::post('/logout', [AuthUserController::class , 'logout'])->name('logout');

});

Route::group(['middleware' => 'CheckJwtAuth:api'], function () {
    Route::post('verify-user', [VerificationCodeController::class, 'verify']);
});
