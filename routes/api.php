<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('products',ProductController::class);

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/signup', 'App\Http\Controllers\AuthController@signup');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
