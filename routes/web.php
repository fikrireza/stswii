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
	->name('ProviderController.index');
Route::get('/provider/add', 'ProviderController@add')
	->name('ProviderController.add');
Route::post('/provider/add', 'ProviderController@store')
	->name('ProviderController.store');
Route::post('/provider/update', 'ProviderController@update')
	->name('ProviderController.update');
Route::get('/provider/delete/{id}', 'ProviderController@delete')
	->name('ProviderController.delete');

