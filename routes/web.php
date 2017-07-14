<?php

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
    return view('index');
});


Route::get('/provider', 'ProviderController@index')
	->name('provider.index');
Route::post('/provider/store', 'ProviderController@store')
	->name('provider.store');
Route::post('/provider/update', 'ProviderController@update')
	->name('provider.update');
Route::get('/provider/delete/{id}', 'ProviderController@delete')
	->name('provider.delete');

Route::get('/provider-prefix', 'ProviderPrefixController@index')
	->name('provider-prefix.index');
Route::post('/provider-prefix/store', 'ProviderPrefixController@store')
	->name('provider-prefix.store');
Route::post('/provider-prefix/update', 'ProviderPrefixController@update')
	->name('provider-prefix.update');
Route::get('/provider-prefix/delete/{id}', 'ProviderPrefixController@delete')
	->name('provider-prefix.delete');


Route::get('product', 'ProductController@index')->name('product.index');
Route::get('product/add', 'ProductController@tambah')->name('product.tambah');
Route::post('product', 'ProductController@store')->name('product.store');
Route::get('product/edit/{product_code}', 'ProductController@ubah')->name('product.ubah');
Route::post('product/edit', 'ProductController@update')->name('product.update');
