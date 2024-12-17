<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/order',  [ApiOrderController::class, "index"]);