<?php
use Modules\Auth\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest.check'])->group(function () {
    Route::get('/login', [AuthController::class,'showLoginForm'])->name('login');
    Route::post('/login-user', [AuthController::class,'loginUser'])->name('login.user');
    Route::post('/create-user', [AuthController::class,'createUser'])->name('create.user');
});

Route::middleware(['auth.check'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logoutUser'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('user.changepassword');
    Route::post('/set-password', [AuthController::class, 'setPassword'])->name('user.setpassword');
});

Route::prefix('user')->group(function() {
    Route::middleware(['guest.check'])->group(function () {
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    });
    
    Route::middleware(['auth.check'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'userDashboard'])->name('user.dashboard');
    });
});

Route::prefix('vendor')->group(function() {
    Route::middleware(['guest.check'])->group(function () {
        Route::get('/register', [AuthController::class, 'showVendorRegistration'])->name('vendor.register');
    });
    Route::middleware(['auth.check'])->group(function () {
        Route::get('/dashbaord', [AuthController::class,'vendorDashboard'])->name('vendor.dashboard');
    });
});


