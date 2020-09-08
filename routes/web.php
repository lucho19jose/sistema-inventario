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
//Route::resource('categories', 'Admin\CategoryController')->except('show', 'create', 'edit');
Route::middleware(['auth'])->group(function () {
	Route::get('categories', 'Admin\CategoryController@index')->name('categories.index')
		->middleware('permission:category.index');
	Route::post('categories', 'Admin\CategoryController@store')->name('categories.store')
		->middleware('permission:category.create');
	Route::put('categories/{category}', 'Admin\CategoryController@update')->name('categories.update')
		->middleware('permission:category.update');
	Route::delete('categories/{category}', 'Admin\CategoryController@destroy')->name('categories.destroy')
		->middleware('permission:category.destroy');
});

//Rutas de productos
//Route::resource('products', 'Admin\ProductController')->except('show', 'edit');
Route::middleware(['auth'])->group(function () {
	Route::get('products', 'Admin\ProductController@index')->name('products.index')
		->middleware('permission:product.index');
	Route::post('products', 'Admin\ProductController@store')->name('products.store')
		->middleware('permission:product.create');
	Route::put('products/{product}', 'Admin\ProductController@update')->name('products.update')
		->middleware('permission:product.update');
	Route::delete('products/{product}', 'Admin\ProductController@destroy')->name('products.destroy')
		->middleware('permission:product.destroy');
	Route::get('products/create', 'Admin\ProductController@create')->name('products.create');
	Route::get('products/searchProduct', 'Admin\ProductController@searchProduct');
	Route::get('products/getProduct', 'Admin\ProductController@getProduct');
	Route::get('products/stockReport', 'Admin\ProductController@stockReport')
		->middleware('permission:product.report');
});

//Rutas proveedores
//Route::resource('providers', 'Admin\ProviderController')->except('show', 'create', 'edit');
Route::middleware(['auth'])->group(function () {
	Route::get('providers', 'Admin\providerController@index')->name('providers.index')
		->middleware('permission:provider.index');
	Route::post('providers', 'Admin\providerController@store')->name('providers.store')
		->middleware('permission:provider.create');
	Route::put('providers/{provider}', 'Admin\providerController@update')->name('providers.update')
		->middleware('permission:provider.update');
	Route::delete('providers/{provider}', 'Admin\providerController@destroy')->name('providers.destroy')
		->middleware('permission:provider.destroy');
	Route::get('providers/selectProvider', 'Admin\providerController@selectProvider');
});

//Rutas de los personales
//Route::resource('staffs', 'Admin\StaffController')->except('show', 'create', 'edit');
Route::middleware(['auth'])->group(function () {
	Route::get('staffs', 'Admin\StaffController@index')->name('staffs.index')
		->middleware('permission:staff.index');
	Route::post('staffs', 'Admin\StaffController@store')->name('staffs.store')
		->middleware('permission:staff.create');
	Route::put('staffs/{staff}', 'Admin\StaffController@update')->name('staffs.update')
		->middleware('permission:staff.update');
	Route::delete('staffs/{staff}', 'Admin\StaffController@destroy')->name('staffs.destroy')
		->middleware('permission:staff.destroy');
	Route::get('staffs/selectStaff', 'Admin\StaffController@selectStaff');
});

//Rutas de las Ingresos de productos
//Route::resource('inputs', 'Admin\inputController')->only('index', 'store');
Route::middleware(['auth'])->group(function () {
	Route::get('inputs', 'Admin\inputController@index')->name('inputs.index')
		->middleware('permission:input.index');
	Route::post('inputs', 'Admin\inputController@store')->name('inputs.store')
		->middleware('permission:input.create');
	Route::get('inputs/showInput', 'Admin\inputController@showInput')
		->middleware('permission:input.show');
	Route::get('inputs/inputReport', 'Admin\inputController@inputReport')
		->middleware('permission:input.report');
});

//Rutas de las sucursales
//Route::resource('branches', 'Admin\BranchController')->except('show', 'create', 'edit');
Route::middleware(['auth'])->group(function () {
	Route::get('branches', 'Admin\BranchController@index')->name('branches.index')
		->middleware('permission:branch.index');
	Route::post('branches', 'Admin\BranchController@store')->name('branches.store')
		->middleware('permission:branch.create');
	Route::put('branches/{branch}', 'Admin\BranchController@update')->name('branches.update')
		->middleware('permission:branch.update');
	Route::delete('branches/{branch}', 'Admin\BranchController@destroy')->name('branches.destroy')
		->middleware('permission:branch.destroy');
	Route::get('branches/selectBranch', 'Admin\BranchController@selectBranch');
});

//Rutas rutas ded las salidad de productos
//Route::resource('outputs', 'Admin\OutputController')->only('index', 'store');
Route::middleware(['auth'])->group(function () {
	Route::get('outputs', 'Admin\OutputController@index')->name('outputs.index')
		->middleware('permission:output.index');
	Route::post('outputs', 'Admin\OutputController@store')->name('outputs.store')
		->middleware('permission:output.create');
	Route::get('outputs/showOutput', 'Admin\OutputController@showOutput')
		->middleware('permission:output.show');
	Route::get('outputs/outputReport', 'Admin\OutputController@outputReport')
		->middleware('permission:output.report');
});

//Rutas de los metodos del usuario
//Route::resource('users', 'Admin\UserController')->except('show', 'edit');
Route::middleware(['auth'])->group(function () {
	Route::get('users', 'Admin\UserController@index')->name('users.index')
		->middleware('permission:user.index');
	Route::post('users', 'Admin\UserController@store')->name('users.store')
		->middleware('permission:user.create');
	Route::put('users/{user}', 'Admin\UserController@update')->name('users.update')
		->middleware('permission:user.update');
	Route::get('users/{user}/reset', 'Admin\UserController@reset')->name('users.reset')
		->middleware('permission:user.reset');
	Route::delete('users/{user}', 'Admin\UserController@destroy')->name('users.destroy')
		->middleware('permission:user.destroy');
	Route::get('users/create', 'Admin\UserController@create')->name('users.create');
});

//Roles y Permisos
//Route::resource('roles', 'Admin\RoleController')->except('show');
Route::middleware(['auth'])->group(function () {
	Route::get('roles', 'Admin\RoleController@index')->name('roles.index')
		->middleware('permission:role.index');
	Route::post('roles', 'Admin\RoleController@store')->name('roles.store')
		->middleware('permission:role.create');
	Route::put('roles/{role}', 'Admin\RoleController@update')->name('roles.update')
		->middleware('permission:role.update');
	Route::delete('roles/{role}', 'Admin\RoleController@destroy')->name('roles.destroy')
		->middleware('permission:role.destroy');
	Route::get('roles/create', 'Admin\RoleController@create')->name('roles.create');
	Route::get('roles/{role}/edit', 'Admin\RoleController@edit')->name('roles.edit');
});