<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
Route::post('/product', [ProductController::class, 'store2'])->name('product.store');

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->name('any');
