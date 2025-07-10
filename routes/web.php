<?php

use Illuminate\Support\Facades\Route;


Route::get('/product', function () {
    return view('product.create');
})->name('add-product');

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
