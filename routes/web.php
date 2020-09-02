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

//Rutas categorias
Route::resource('categories', 'Admin\CategoryController')->except('show', 'create', 'edit');

//Rutas de productos
Route::resource('products', 'Admin\ProductController')->except('show', 'edit');
Route::get('products/searchProduct', 'Admin\ProductController@searchProduct');
Route::get('products/getProduct', 'Admin\ProductController@getProduct');
Route::get('products/stockReport', 'Admin\ProductController@stockReport');

//Rutas proveedores
Route::resource('providers', 'Admin\ProviderController')->except('show', 'create', 'edit');
Route::get('providers/selectProvider', 'Admin\providerController@selectProvider');

//Rutas de los personales
Route::resource('staffs', 'Admin\StaffController')->except('show', 'create', 'edit');
Route::get('staffs/selectStaff', 'Admin\StaffController@selectStaff');

//Rutas de las Ingresos de productos
Route::resource('inputs', 'Admin\inputController')->only('index', 'store');
Route::get('inputs/showInput', 'Admin\inputController@showInput');
Route::get('inputs/inputReport', 'Admin\inputController@inputReport');

//Rutas de las sucursales
Route::resource('branches', 'Admin\BranchController')->except('show', 'create', 'edit');
Route::get('branches/selectBranch', 'Admin\BranchController@selectBranch');

//Rutas rutas ded las salidad de productos
Route::resource('outputs', 'Admin\OutputController')->only('index', 'store');
Route::get('outputs/showOutput', 'Admin\OutputController@showOutput');
Route::get('outputs/outputReport', 'Admin\OutputController@outputReport');

Route::resource('users', 'Admin\UserController')->except('show', 'edit');