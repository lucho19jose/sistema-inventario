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

Route::redirect('/', 'home');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('categories', 'Admin\CategoryController')->except('show', 'create', 'edit');

Route::resource('products', 'Admin\ProductController')->except('show', 'edit');

Route::resource('providers', 'Admin\ProviderController')->except('show', 'create', 'edit');
