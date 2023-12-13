<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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

Route::get('/',         [PaymentController::class, 'index'])->name('index.payment');
Route::post('/cart-items', [PaymentController::class, 'itemManage']);
Route::get('/card-payament',         [PaymentController::class, 'cardPayment']);
Route::post('/cart-items', [PaymentController::class, 'itemManage']);
Route::get('/payment-sucessfull',         [PaymentController::class, 'paymentSucessfully']);
Route::post('/payment', [PaymentController::class, 'payment']);
