<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'index'])
    ->name('main');
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->name('products.show');
Route::get('/auth/login', [AuthController::class, 'login'])
    ->name('auth.login');

require __DIR__ . '/admin.php';
