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

Route::get('/home', 'HomeController@index')
	->name('home.index');

// provider
	Route::get('/provider', 'ProviderController@index')
		->name('provider.index');
	Route::post('/provider/store', 'ProviderController@store')
		->name('provider.store');
	Route::post('/provider/update', 'ProviderController@update')
		->name('provider.update');
	Route::get('/provider/delete/{id}/{version}', 'ProviderController@delete')
		->name('provider.delete');

	Route::get('/provider/ajax-view/{id}', 'ProviderController@ajaxView')
		->name('provider.ajax.view');

	Route::get('/provider/yajra/getDatas', 'ProviderController@yajraGetData')
		->name('provider.yajra.getDatas');
// provider

// provider prefix
	Route::get('/provider-prefix', 'ProviderPrefixController@index')
		->name('provider-prefix.index');
	Route::post('/provider-prefix/store', 'ProviderPrefixController@store')
		->name('provider-prefix.store');
	Route::post('/provider-prefix/update', 'ProviderPrefixController@update')
		->name('provider-prefix.update');
	Route::get('/provider-prefix/delete/{id}/{version}', 'ProviderPrefixController@delete')
		->name('provider-prefix.delete');
// provider prefix

// agent
	Route::get('/agent', 'AgentController@index')
		->name('agent.index');

	Route::get('/agent/getDatas', 'AgentController@getDataTables')
		->name('agent.getDatas');

	Route::get('/agent/edit/{id}', 'AgentController@edit')
		->name('agent.edit');

	Route::post('/agent/update', 'AgentController@update')
		->name('agent.update');

	Route::get('/agent/seed-db', 'AgentController@seedTables');
// agent

// paloma deposit transaction
	Route::get('/paloma-deposit/transaction', function(){
		return view('paloma-transaction.index');
	})->name('paloma.transaction.index');
// paloma deposit transaction

// partner pulsa
	Route::get('/partner-pulsa', 'PartnerPulsaController@index')
		->name('partner-pulsa.index')->middleware('can:read-partner-pulsa');
	Route::get('/partner-pulsa/create', 'PartnerPulsaController@create')
		->name('partner-pulsa.create')->middleware('can:create-partner-pulsa');
	Route::post('/partner-pulsa/store', 'PartnerPulsaController@store')
		->name('partner-pulsa.store')->middleware('can:create-partner-pulsa');
	Route::get('/partner-pulsa/edit/{id}/{version}', 'PartnerPulsaController@edit')
		->name('partner-pulsa.edit')->middleware('can:update-partner-pulsa');
	Route::post('/partner-pulsa/update/{id}/{version}', 'PartnerPulsaController@update')
		->name('partner-pulsa.update')->middleware('can:update-partner-pulsa');
	Route::get('/partner-pulsa/delete/{id}/{version}', 'PartnerPulsaController@delete')
		->name('partner-pulsa.delete')->middleware('can:delete-partner-pulsa');
	Route::get('/partner-pulsa/actived/{id}/{version}/{status}', 'PartnerPulsaController@active')
		->name('partner-pulsa.active')->middleware('can:activate-partner-pulsa');
// partner pulsa

// partner product
	Route::get('/partner-product', 'PartnerProductController@index')
		->name('partner-product.index')->middleware('can:read-partner-product');
	Route::get('/partner-product/create', 'PartnerProductController@create')
		->name('partner-product.create')->middleware('can:create-partner-product');
	Route::post('/partner-product/store', 'PartnerProductController@store')
		->name('partner-product.store')->middleware('can:create-partner-product');
	Route::get('/partner-product/edit/{id}/{version}', 'PartnerProductController@edit')
		->name('partner-product.edit')->middleware('can:update-partner-product');
	Route::post('/partner-product/update/{id}/{version}', 'PartnerProductController@update')
		->name('partner-product.update')->middleware('can:update-partner-product');
	Route::get('/partner-product/delete/{id}/{version}', 'PartnerProductController@delete')
		->name('partner-product.delete')->middleware('can:delete-partner-product');
	Route::get('/partner-product/actived/{id}/{version}/{status}', 'PartnerProductController@active')
		->name('partner-product.active')->middleware('can:activate-partner-product');

	Route::get('/partner-product/ajaxGetProductList/{id?}', 'PartnerProductController@ajaxGetProductList')
		->name('partner-product.ajaxGetProductList');
// partner product

// partner product purch price
	Route::get('/partner-product-purch-price', 'PartnerProductPurchPriceController@index')
		->name('partner-product-purch-price.index')->middleware('can:read-partner-product-purch-price');

	Route::get('/partner-product-purch-price/actived/{id}/{version}/{status}', 'PartnerProductPurchPriceController@active')
		->name('partner-product-purch-price.active')->middleware('can:activate-partner-product-purch-price');

	Route::get('/partner-product-purch-price/delete/{id}/{version}', 'PartnerProductPurchPriceController@delete')
		->name('partner-product-purch-price.delete')->middleware('can:delete-partner-product-purch-price');

	Route::get('/partner-product-purch-price/add', 'PartnerProductPurchPriceController@tambah')
		->name('partner-product-purch-price.tambah')->middleware('can:create-partner-product-purch-price');
	Route::post('/partner-product-purch-price/store', 'PartnerProductPurchPriceController@store')
		->name('partner-product-purch-price.store')->middleware('can:create-partner-product-purch-price');

	Route::get('/partner-product-purch-price/edit/{id}/{version}', 'PartnerProductPurchPriceController@edit')
		->name('partner-product-purch-price.edit')->middleware('can:update-partner-product-purch-price');
	Route::post('/partner-product-purch-price/edit/{id}/{version}', 'PartnerProductPurchPriceController@update')
		->name('partner-product-purch-price.update')->middleware('can:update-partner-product-purch-price');

	Route::get('/partner-product-purch-price/ajax-get-product-partner/{partner?}/{provider?}', 'PartnerProductPurchPriceController@ajaxGetProductPartner')
		->name('partner-product-purch-price.ajaxGetProductPartner');

	Route::get('partner-product-purch-price/upload', 'PartnerProductPurchPriceController@upload')
	  ->name('partner-product-purch-price.upload')->middleware('can:create-partner-product-purch-price');
// partner product purch price

// partner server
	Route::get('/partner-server', 'PartnerPulsaServerPropController@index')
		->name('partner-server.index')->middleware('can:read-partner-server');
	Route::post('/partner-server/store', 'PartnerPulsaServerPropController@store')
		->name('partner-server.store')->middleware('can:create-partner-server');
	Route::post('/partner-server/update', 'PartnerPulsaServerPropController@update')
		->name('partner-server.update')->middleware('can:update-partner-server');
	Route::get('/partner-server/delete/{id}', 'PartnerPulsaServerPropController@delete')
		->name('partner-server.delete')->middleware('can:delete-partner-server');
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
	Route::get('product-sell-price/upload/store-template', 'ProductSellPriceController@storeTemplate')
	  ->name('product-sell-price.storeTemplate');
//----- PRODUCT SELL PRICE -----//

//----- Management Account -----//
	Route::get('account', 'AccountController@index')->name('account.index')->middleware('can:read-user');
	Route::get('account/add', 'AccountController@tambah')->name('account.tambah')->middleware('can:create-user');
	Route::post('account/add', 'AccountController@store')->name('account.store')->middleware('can:create-user');
	Route::get('account/edit/{id}', 'AccountController@ubah')->name('account.ubah')->middleware('can:update-user');
	Route::post('account/edit', 'AccountController@update')->name('account.update')->middleware('can:update-user');
	Route::get('account/role', 'AccountController@role')->name('account.role')->middleware('can:read-role');
	Route::get('account/role/{id}', 'AccountController@roleUbah')->name('account.roleUbah')->middleware('can:update-role');
	Route::post('account/role', 'AccountController@roleEdit')->name('account.roleEdit')->middleware('can:update-role');
	Route::get('account/reset/{id}', 'AccountController@reset')->middleware('can:reset-user');
	Route::get('account/actived/{id}', 'AccountController@activate')->middleware('can:activate-user');
//----- Management Account -----//

//------- Deposit Agent -------//
Route::get('deposit-agent-confirm', 'DepositAgentController@indexConfirm')->name('deposit-agent-confirm.index');
Route::post('deposit-agent-confirm', 'DepositAgentController@getUniqueCode')->name('deposit-agent-confirm.getUniqueCode');
Route::post('deposit-agent-proses-confirm', 'DepositAgentController@confirm')->name('deposit-agent.confirm');
Route::get('getUnconfirmedUniqueCodes', 'DepositAgentController@getUnconfirmedUniqueCodes');


Route::get('deposit-agent-confirmed-top-up', 'DepositAgentController@indexConfirmTopUp')->name('deposit-agent-reversal.index');
Route::post('deposit-agent-confirmed-top-up', 'DepositAgentController@getRangeDate')->name('deposit-agent-reversal.getRangeDate');
Route::post('deposit-agent-proses-reversal', 'DepositAgentController@reversalTrx')->name('deposit-agent-reversal.reversalTrx');
Route::get('getConfirmedTopUp', 'DepositAgentController@getConfirmedTopUp');
