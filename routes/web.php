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

Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm');
// Route::get('/', 'HomeController@login')
// 	->name('login');

Route::get('/home', 'HomeController@index')
	->name('home.index');

// provider
	Route::get('/provider', 'ProviderController@index')
		->name('provider.index');
	Route::post('/provider/store', 'ProviderController@store')
		->name('provider.store');
	Route::post('/provider/update', 'ProviderController@update')
		->name('provider.update');
	Route::get('/provider/delete/{id}', 'ProviderController@delete')
		->name('provider.delete');
// provider

// provider prefix
	Route::get('/provider-prefix', 'ProviderPrefixController@index')
		->name('provider-prefix.index');
	Route::post('/provider-prefix/store', 'ProviderPrefixController@store')
		->name('provider-prefix.store');
	Route::post('/provider-prefix/update', 'ProviderPrefixController@update')
		->name('provider-prefix.update');
	Route::get('/provider-prefix/delete/{id}', 'ProviderPrefixController@delete')
		->name('provider-prefix.delete');
// provider prefix

// agent
	Route::get('/agent', /*'AgentController@index'*/
		function(){
			return view('agent.index');
		})
		->name('agent.index');
	Route::get('/agent/edit/{id}', /*'AgentController@edit'*/
		function(){
			return view('agent.ubah');
		})
		->name('agent.edit');
	Route::post('/agent/update', /*'AgentController@update'*/
		function(){

		})
		->name('agent.update');
// agent

// paloma deposit transaction
	Route::get('/paloma-deposit/transaction', function(){
		return view('paloma-transaction.index');
	})->name('paloma.transaction.index');
// paloma deposit transaction

// partner pulsa
	Route::get('/partner-pulsa', 'PartnerPulsaController@index')
		->name('partner-pulsa.index');
	Route::get('/partner-pulsa/create', 'PartnerPulsaController@create')
		->name('partner-pulsa.create');
	Route::post('/partner-pulsa/store', 'PartnerPulsaController@store')
		->name('partner-pulsa.store');
	Route::get('/partner-pulsa/edit/{id}', 'PartnerPulsaController@edit')
		->name('partner-pulsa.edit');
	Route::post('/partner-pulsa/update/{id}', 'PartnerPulsaController@update')
		->name('partner-pulsa.update');
	Route::get('/partner-pulsa/delete/{id}', 'PartnerPulsaController@delete')
		->name('partner-pulsa.delete');
	Route::get('/partner-pulsa/active/{id}', 'PartnerPulsaController@active')
		->name('partner-pulsa.active');
// partner pulsa

// partner product
	Route::get('/partner-product', 'PartnerProductController@index')
		->name('partner-product.index');
	Route::get('/partner-product/create', 'PartnerProductController@create')
		->name('partner-product.create');
	Route::post('/partner-product/store', 'PartnerProductController@store')
		->name('partner-product.store');
	Route::get('/partner-product/edit/{id}', 'PartnerProductController@edit')
		->name('partner-product.edit');
	Route::post('/partner-product/update/{id}', 'PartnerProductController@update')
		->name('partner-product.update');
	Route::get('/partner-product/delete/{id}', 'PartnerProductController@delete')
		->name('partner-product.delete');
	Route::get('/partner-product/active/{id}', 'PartnerProductController@active')
		->name('partner-product.active');
	Route::get('/partner-product/ajaxGetProductList/{id?}', 'PartnerProductController@ajaxGetProductList')
		->name('partner-product.ajaxGetProductList');
// partner product

// partner product purch price
	Route::get('/partner-product-purch-price', 'PartnerProductPurchPriceController@index')
		->name('partner-product-purch-price.index');
	Route::get('/partner-product-purch-price/active/{id}', 'PartnerProductPurchPriceController@active')
		->name('partner-product-purch-price.active');
	Route::get('/partner-product-purch-price/delete/{id}', 'PartnerProductPurchPriceController@delete')
		->name('partner-product-purch-price.delete');
	Route::get('/partner-product-purch-price/add', 'PartnerProductPurchPriceController@tambah')
		->name('partner-product-purch-price.tambah');
	Route::post('/partner-product-purch-price/store', 'PartnerProductPurchPriceController@store')
		->name('partner-product-purch-price.store');
	Route::get('/partner-product-purch-price/edit/{id}', 'PartnerProductPurchPriceController@edit')
		->name('partner-product-purch-price.edit');
	Route::post('/partner-product-purch-price/edit', 'PartnerProductPurchPriceController@update')
		->name('partner-product-purch-price.update');
	Route::get('partner-product-purch-price/upload', 'PartnerProductPurchPriceController@upload')
	  ->name('partner-product-purch-price.upload');
// partner product purch price


// partner server
	Route::get('/partner-server', 'PartnerPulsaServerPropController@index')
		->name('partner-server.index');
	Route::post('/partner-server/store', 'PartnerPulsaServerPropController@store')
		->name('partner-server.store');
	Route::post('/partner-server/update', 'PartnerPulsaServerPropController@update')
		->name('partner-server.update');
	Route::get('/partner-server/delete/{id}', 'PartnerPulsaServerPropController@delete')
		->name('partner-server.delete');
// partner server

//----- START PRODUCT -----//
	Route::get('product', 'ProductController@index')
		->name('product.index');
	Route::get('product/add', 'ProductController@tambah')
		->name('product.tambah');
	Route::post('product', 'ProductController@store')
		->name('product.store');
	Route::get('product/edit/{product_code}', 'ProductController@ubah')
		->name('product.ubah');
	Route::post('product/edit', 'ProductController@update')
		->name('product.update');
	Route::get('product/active/{id}', 'ProductController@active')
		->name('product.active');
	Route::get('product/delete/{id}', 'ProductController@delete')
		->name('product.delete');
//----- PRODUCT -----//

//----- PRODUCT SELL PRICE -----//
	Route::get('product-sell-price', 'ProductSellPriceController@index')
	  ->name('product-sell-price.index');
	Route::get('product-sell-price/add', 'ProductSellPriceController@tambah')
	  ->name('product-sell-price.tambah');
	Route::get('product-sell-price/product/{id}', 'ProductSellPriceController@bindProduct')
	  ->name('product-sell-price.bindProduct');
	Route::post('product-sell-price', 'ProductSellPriceController@store')
	  ->name('product-sell-price.store');
	Route::get('product-sell-price/edit/{id}', 'ProductSellPriceController@ubah')
	  ->name('product-sell-price.ubah');
	Route::post('product-sell-price/edit', 'ProductSellPriceController@update')
	  ->name('product-sell-price.edit');
	Route::get('product-sell-price/active/{id}', 'ProductSellPriceController@active')
	  ->name('product-sell-price.active');
	Route::get('product-sell-price/delete/{id}', 'ProductSellPriceController@delete')
	  ->name('product-sell-price.delete');

	  //----- Upload Excel
	Route::get('product-sell-price/upload', 'ProductSellPriceController@upload')
	  ->name('product-sell-price.upload');
	Route::get('product-sell-price/upload/template', 'ProductSellPriceController@template')
	  ->name('product-sell-price.template');
	Route::post('product-sell-price/upload/template', 'ProductSellPriceController@prosesTemplate')
	  ->name('product-sell-price.prosesTemplate');
	Route::post('product-sell-price/upload', 'ProductSellPriceController@storeTemplate')
	  ->name('product-sell-price.storeTemplate');
//----- PRODUCT SELL PRICE -----//

//----- Management Account -----//
	Route::get('account', 'AccountController@index')->name('account.index')->middleware('can:user-read');
	Route::get('account/add', 'AccountController@tambah')->name('account.tambah')->middleware('can:user-create');
	Route::get('account/edit/{id}', 'AccountController@ubah')->name('account.ubah')->middleware('can:user-update');
	Route::get('account/role', 'AccountController@role')->name('account.role')->middleware('can:role-read');
	Route::get('account/role/{id}', 'AccountController@roleUbah')->name('account.roleUbah')->middleware('can:role-update');
	Route::post('account/role', 'AccountController@roleEdit')->name('account.roleEdit')->middleware('can:role-update');
//----- Management Account -----//
