<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuotationsController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->post('/quotation', [QuotationsController::class, 'getQuote']);