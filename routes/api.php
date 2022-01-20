<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Categories
    Route::get('categories', 'CategoryController@index');
    Route::get('categories/random/{count}', 'CategoryController@random');
    Route::get('categories/slug/{slug}', 'CategoryController@slug');

    // Books
    Route::get('books', 'BookController@index');
    Route::get('books/top/{count}', 'BookController@top');
    Route::get('books/slug/{slug}', 'BookController@slug');
    Route::get('books/search/{keyword}', 'BookController@search');

    // Auth
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');

    // Shop
    Route::get('provinces', 'ShopController@provinces');
    Route::get('cities', 'ShopController@cities');
    Route::get('couriers', 'ShopController@couriers');

    // private
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', function (Request $request) {
            return $request->user();
        });

        //Shop
        Route::post('shipping', 'ShopController@shipping');
        Route::post('services', 'ShopController@services');
        Route::post('payment', 'ShopController@payment');

        // Shop History Order
        Route::get('my-order', 'ShopController@myOrder');
    });
});
