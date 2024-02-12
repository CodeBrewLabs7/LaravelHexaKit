<?php
use Modules\DeliveryOptions\Http\Controllers\DeliveryOptionController;

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


    Route::prefix('adminpanel/delivery')->middleware(['auth.check'])->group(function() {
        Route::get('/', [DeliveryOptionController::class, 'index'])->name('deliveryoptions.index');
        Route::get('create', [DeliveryOptionController::class, 'create'])->name('deliveryoptions.create');
        Route::post('store', [DeliveryOptionController::class,'store'])->name('deliveryoptions.store');
        Route::get('edit/{id}', [DeliveryOptionController::class, 'edit'])->name('deliveryoptions.edit');
        Route::post('update/{id}', [DeliveryOptionController::class, 'update'])->name('deliveryoptions.update');
        Route::get('delete/{id}', [DeliveryOptionController::class, 'delete'])->name('deliveryoptions.delete');

    });

//Webhook Controller and routes names
Route::get('webhook/shiprocket', [DeliveryOptionController::class,'webhook'])->name('webhook.shiprocket');
Route::get('webhook/lalamove', [DeliveryOptionController::class,'webhook'])->name('webhook.lalamove');
Route::get('webhook/danzo', [DeliveryOptionController::class,'webhook'])->name('webhook.danzo');
Route::get('webhook/roadie', [DeliveryOptionController::class,'webhook'])->name('webhook.roadie');
Route::get('webhook/ahoy', [DeliveryOptionController::class,'webhook'])->name('webhook.ahoy');
Route::get('webhook/shippo', [DeliveryOptionController::class,'webhook'])->name('webhook.shippo');

Route::post('webhook/set','AhoyController@setWebhook')->name('setWebhook');

