<?php

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
Route::middleware(['auth.check'])->group(function () {
    Route::prefix('adminpanel')->group(function() {
        Route::get('/dashboard', 'AdminPanelController@index')->name('adminpanel.dashboard');
    });
});