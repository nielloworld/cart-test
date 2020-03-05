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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', 'ProductsController@index');
Route::get('/cart', 'ProductsController@cart');



Route::patch('update-cart', 'ProductsController@update');
Route::delete('remove-from-cart', 'ProductsController@remove');
Route::get('/add-to-cart/{id}', 'ProductsController@addToCart');
