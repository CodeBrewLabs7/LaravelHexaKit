<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
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

Route::post('check-user-status', [AuthController::class, 'checkUserStatus']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('upload-file', [UserController::class, 'uploadFile']);
    Route::post('upload-user-documents', [UserController::class, 'uploadUserDocuments']);
    Route::get('get-profile', [UserController::class, 'getProfile']);
    Route::post('edit-profile', [UserController::class, 'editProfile']);
});
