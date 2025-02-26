<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\OrderController;

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('get-orders', [OrderController::class, 'getOrders']);
    Route::post('delete-order', [OrderController::class, 'deleteOrder']);
    Route::post('create-order', [OrderController::class, 'createOrder']);
});

Route::post('auth/login', [LoginController::class, 'index']);