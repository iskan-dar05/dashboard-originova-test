<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\subadmin\ProductController;
use App\Http\Controllers\subadmin\UserController;
use App\Http\Controllers\subadmin\OrderController;
use App\Http\Controllers\subadmin\CategoryController;






Route::prefix('subadmin')->group(function () {
	Route::post('/user/{user}', [UserController::class, 'blockUser']);
	

});

Route::prefix('subadmin')->group(function () {
		Route::get('/product', [ProductController::class, 'index']);
	Route::post('/product', [ProductController::class, 'store']);
	Route::get('/product/{product}', [ProductController::class, 'show']);
	Route::post('/product/{product}', [ProductController::class, 'update']);
	Route::delete('/product/{product}', [ProductController::class, 'destroy']);


});
Route::prefix('subadmin')->group(function () {
	Route::get('/order', [OrderController::class, 'index']);
	Route::post('/order', [OrderController::class, 'store']);
	Route::get('/order/{order}', [OrderController::class, 'show']);
	Route::put('/order/{order}', [OrderController::class, 'update']);
	Route::delete('/order/{order}', [OrderController::class, 'destroy']);

});
Route::prefix('subadmin')->group(function () {
	Route::get('/category', [CategoryController::class, 'index']);
	Route::post('/category', [CategoryController::class, 'store']);
	Route::get('/category/{category}', [CategoryController::class, 'show']);
	Route::put('/category/{category}', [CategoryController::class, 'update']);
	Route::delete('/category/{category}', [CategoryController::class, 'destroy']);

});




