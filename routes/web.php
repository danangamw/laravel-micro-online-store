<?php

use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/cart', [CartController::class, 'index']);
Route::get('/cart/delete', [CartController::class, 'delete']);
Route::post('/cart/add/{id}', [CartController::class, 'add']);

Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminHomeController::class, 'index']);
    Route::get('/admin/product', [AdminProductController::class, 'index']);
    Route::post('/admin/product/store', [AdminProductController::class, 'store']);
    Route::delete('/admin/product/{id}/delete', [AdminProductController::class, 'delete']);
    Route::get('/admin/product/{id}/edit', [AdminProductController::class, 'edit']);
    Route::put('/admin/product/{id}/update', [AdminProductController::class, 'update']);
    Route::get('/cart/purchase', [CartController::class, 'purchase']);
});

Auth::routes();
