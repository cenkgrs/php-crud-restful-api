<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\OrderController;

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('get-orders', [OrderController::class, 'getOrders']);
    Route::post('delete-order', [OrderController::class, 'deleteOrder']);
    Route::post('create-order', [OrderController::class, 'createOrder']);

    Route::post('check-discount', [OrderController::class, 'checkDiscount']);
    Route::post('create-discount-rule', [OrderController::class, 'createDiscountRule']);
});

Route::get('auth/login', ['as' => 'login', function() {
    return response()->json(['status' => false, 'message' => 'Lütfen önce login olup token alınız']);
}]);

Route::post('auth/login', [LoginController::class, 'index']);