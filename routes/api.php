<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::post('auth/login', [LoginController::class, 'index']);