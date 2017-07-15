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

Route::get('/partner-pulsa', 'PartnerPulsaController@index')->name('partner-pulsa.index');
Route::get('/partner-pulsa/create', 'PartnerPulsaController@create')->name('partner-pulsa.create');
Route::post('/partner-pulsa/store', 'PartnerPulsaController@store')->name('partner-pulsa.store');
Route::get('/partner-pulsa/edit/{id}', 'PartnerPulsaController@edit')->name('partner-pulsa.edit');
Route::post('/partner-pulsa/update/{id}', 'PartnerPulsaController@update')->name('partner-pulsa.update');
Route::get('/partner-pulsa/delete/{id}', 'PartnerPulsaController@delete')->name('partner-pulsa.delete');
Route::get('/partner-pulsa/active/{id}', 'PartnerPulsaController@active')->name('partner-pulsa.active');

Route::get('/partner-product', 'PartnerProductController@index')->name('partner-product.index');
Route::get('/partner-product/create', 'PartnerProductController@create')->name('partner-product.create');
Route::post('/partner-product/store', 'PartnerProductController@store')->name('partner-product.store');
Route::get('/partner-product/edit/{id}', 'PartnerProductController@edit')->name('partner-product.edit');
Route::post('/partner-product/update/{id}', 'PartnerProductController@update')->name('partner-product.update');
Route::get('/partner-product/delete/{id}', 'PartnerProductController@delete')->name('partner-product.delete');
Route::get('/partner-product/active/{id}', 'PartnerProductController@active')->name('partner-product.active');


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
Route::get('product/active/{id}', 'ProductController@active')->name('product.active');
Route::get('product/delete/{id}', 'ProductController@delete')->name('product.delete');
