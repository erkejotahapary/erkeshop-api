<?php

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


Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function() {
    Route::get('/', function () {
        return view('backend.home.home');
    });

    // Book
    Route::get('book/grid', 'BookController@grid');
    Route::resource('/book', 'BookController');

    // User
    Route::get('user/grid', 'UserController@grid');
    Route::resource('/user', 'UserController');
});


Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');
