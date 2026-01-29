<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->name('api.v1.auth.login');
    Route::get('/me', [AuthController::class, 'me'])
        ->middleware('auth:sanctum')
        ->name('api.v1.auth.me');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('api.v1.auth.logout');
});
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])
        ->name('api.v1.products.index');
    Route::post('/', [ProductController::class, 'store'])
        ->middleware('auth:sanctum')
        ->name('api.v1.products.store');
    Route::delete('{id}/force-destroy', [ProductController::class, 'forceDestroy'])
        ->middleware('auth:sanctum')
        ->name('api.v1.products.force-destroy');
    Route::get('/trashed', [ProductController::class, 'trashedIndex'])
        ->middleware('auth:sanctum')
        ->name('api.v1.products.trashed.index');
    Route::post('{id}/restore', [ProductController::class, 'restore'])
        ->middleware('auth:sanctum')
        ->name('api.v1.products.trashed.show.restore');
    Route::get('/{product}', [ProductController::class, 'show'])
        ->name('api.v1.products.show');
    Route::patch('/{product}', [ProductController::class, 'update'])
        ->middleware('auth:sanctum')
        ->name('api.v1.products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])
        ->middleware('auth:sanctum')
        ->name('api.v1.products.destroy');

});
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('api.v1.categories.index');
});
