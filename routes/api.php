<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HotelController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('hotels')->group(function () {
    Route::post('add', [HotelController::class, 'store']);
    Route::delete('/delete/{id}', [HotelController::class, 'destroy']);
    Route::post('update/{id}', [HotelController::class, 'update']);
});

Route::middleware('auth:sanctum')->prefix('categories')->group(function () {
    Route::post('add', [CategoryController::class, 'store']);
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    Route::post('update/{id}', [CategoryController::class, 'update']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/hotels',[HotelController::class,'index']);
Route::get('/hotels/{id}',[HotelController::class,'show']);

Route::get('/categories',[CategoryController::class,'index']);
Route::get('/categories/{id}',[CategoryController::class,'show']);