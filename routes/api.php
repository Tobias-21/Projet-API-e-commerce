<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login',[AuthController::class, 'login']);
Route::post('/auth/register',[AuthController::class,'register']);

Route::middleware('auth:sanctum')->post('/auth/logout',[AuthController::class,'logout']);

Route::middleware('auth:sanctum')->group(function(){
    // Protected routes will be defined here
   Route::apiResource('/products', \App\Http\Controllers\ProductController::class);
   Route::apiResource('/categories', \App\Http\Controllers\CategoryController::class);
   Route::apiResource('/variants', \App\Http\Controllers\ProductVariantsController::class)->except('store');
   Route::post('/products/{product}/variants', [\App\Http\Controllers\ProductVariantsController::class, 'store'])->name('variants.store');
   Route::apiResource('/carts', \App\Http\Controllers\CartController::class)->only('index');
   Route::post('/carts/add',[\App\Http\Controllers\CartController::class, 'store'])->name('carts.add');
   Route::put('/carts/update/{item}',[\App\Http\Controllers\CartController::class, 'update'])->name('item.update');
   Route::delete('/carts/remove/{item}',[\App\Http\Controllers\CartController::class,'destroy'])->name('item.remove');

});