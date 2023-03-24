<?php

use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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

Route::get('/admin', [AdminHomeController::class, 'index']);
Route::get('/admin/product', [AdminProductController::class, 'index']);
Route::post('/admin/product/store', [AdminProductController::class, 'store']);
Route::delete('/admin/product/{id}/delete', [AdminProductController::class, 'delete']);
route::get('/admin/product/{id}/edit', [AdminProductController::class, 'edit']);
route::put('/admin/product/{id}/update', [AdminProductController::class, 'update']);