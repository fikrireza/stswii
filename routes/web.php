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
				->name('provider.index')
				->middleware('can:read-provider');
	Route::post('/provider/store', 'ProviderController@store')
				->name('provider.store')
				->middleware('can:create-provider');
	Route::post('/provider/update', 'ProviderController@update')
				->name('provider.update')
				->middleware('can:update-provider');
	Route::get('/provider/delete/{id}/{version}', 'ProviderController@delete')
				->name('provider.delete')
				->middleware('can:delete-provider');
	Route::get('/provider/ajax-view/{id}', 'ProviderController@ajaxView')
				->name('provider.ajax.view');
	Route::get('/provider/yajra/getDatas', 'ProviderController@yajraGetData')
				->name('provider.yajra.getDatas');
// provider

// provider prefix
	Route::get('/provider-prefix', 'ProviderPrefixController@index')
				->name('provider-prefix.index')
				->middleware('can:read-provider-prefix');
	Route::post('/provider-prefix/store', 'ProviderPrefixController@store')
				->name('provider-prefix.store')
				->middleware('can:create-provider-prefix');
	Route::post('/provider-prefix/update', 'ProviderPrefixController@update')
				->name('provider-prefix.update')
				->middleware('can:update-provider-prefix');
	Route::get('/provider-prefix/delete/{id}/{version}', 'ProviderPrefixController@delete')
				->name('provider-prefix.delete')
				->middleware('can:delete-provider-prefix');
	Route::get('/provider-prefix/yajra/getDatas', 'ProviderPrefixController@yajraGetData')
				->name('provider-prefix.yajra.getDatas');
// provider prefix

// agent
	Route::get('/agent', 'AgentController@index')
				->name('agent.index')->middleware('can:read-agent');
	Route::get('/agent/checkSaldo/{client_id}', 'AgentController@checkSaldo')
				->name('agent.check-saldo')->middleware('can:read-agent');
	Route::get('/agent/resetPin/{client_id}', 'AgentController@resetPin')
				->name('agent.reset-pin')->middleware('can:read-agent');
	Route::get('/agent/getDatas', 'AgentController@getDataTables')
				->name('agent.getDatas');
	Route::get('/agent/edit/{id}', 'AgentController@edit')
				->name('agent.edit')->middleware('can:update-agent');
	Route::post('/agent/update', 'AgentController@update')
				->name('agent.update')->middleware('can:update-agent');
	Route::get('/agent/actived/{id}/{version}/{status}', 'AgentController@active')
				->name('agent.active')->middleware('can:activate-agent');

	Route::get('/agent/seed-db', 'AgentController@seedTables');
// agent

// paloma deposit transaction
	Route::get('/paloma-deposit-transaction', 'PalomaDepositTrxController@index')->name('palomaDeposit.index');
	Route::get('paloma-deposit-transaction/{id}', 'PalomaDepositTrxController@update');
// paloma deposit transaction

// partner pulsa
	Route::get('/partner-pulsa', 'PartnerPulsaController@index')
				->name('partner-pulsa.index')
				->middleware('can:read-partner-pulsa');
	Route::get('/partner-pulsa/create', 'PartnerPulsaController@create')
				->name('partner-pulsa.create')
				->middleware('can:create-partner-pulsa');
	Route::post('/partner-pulsa/store', 'PartnerPulsaController@store')
				->name('partner-pulsa.store')
				->middleware('can:create-partner-pulsa');
	Route::get('/partner-pulsa/edit/{id}/{version}', 'PartnerPulsaController@edit')
				->name('partner-pulsa.edit')
				->middleware('can:update-partner-pulsa');
	Route::post('/partner-pulsa/update/{id}/{version}', 'PartnerPulsaController@update')
				->name('partner-pulsa.update')
				->middleware('can:update-partner-pulsa');
	Route::get('/partner-pulsa/delete/{id}/{version}', 'PartnerPulsaController@delete')
				->name('partner-pulsa.delete')
				->middleware('can:delete-partner-pulsa');
	Route::get('/partner-pulsa/actived/{id}/{version}/{status}', 'PartnerPulsaController@active')
				->name('partner-pulsa.active')
				->middleware('can:activate-partner-pulsa');
	Route::get('/partner-pulsa/yajra/getDatas', 'PartnerPulsaController@yajraGetData')
				->name('partner-pulsa.yajra.getDatas');
// partner pulsa

// partner product
	Route::get('/partner-product', 'PartnerProductController@index')
				->name('partner-product.index')
				->middleware('can:read-partner-product');
	Route::get('/partner-product/create', 'PartnerProductController@create')
				->name('partner-product.create')
				->middleware('can:create-partner-product');
	Route::post('/partner-product/store', 'PartnerProductController@store')
				->name('partner-product.store')
				->middleware('can:create-partner-product');
	Route::get('/partner-product/edit/{id}/{version}', 'PartnerProductController@edit')
				->name('partner-product.edit')
				->middleware('can:update-partner-product');
	Route::post('/partner-product/update/{id}/{version}', 'PartnerProductController@update')
				->name('partner-product.update')
				->middleware('can:update-partner-product');
	Route::get('/partner-product/delete/{id}/{version}', 'PartnerProductController@delete')
				->name('partner-product.delete')
				->middleware('can:delete-partner-product');
	Route::get('/partner-product/actived/{id}/{version}/{status}', 'PartnerProductController@active')
				->name('partner-product.active')
				->middleware('can:activate-partner-product');
	Route::get('/partner-product/ajaxGetProductList/{id?}', 'PartnerProductController@ajaxGetProductList')
				->name('partner-product.ajaxGetProductList');
	Route::get('/partner-product/yajra/getDatas/{request?}', 'PartnerProductController@yajraGetData')
				->name('partner-product.yajra.getDatas');
// partner product

// partner product purch price
	Route::get('/partner-product-purch-price', 'PartnerProductPurchPriceController@index')
				->name('partner-product-purch-price.index')
				->middleware('can:read-partner-product-purch-price');
	Route::get('/partner-product-purch-price/actived/{id}', 'PartnerProductPurchPriceController@active')
				->name('partner-product-purch-price.active')
				->middleware('can:activate-partner-product-purch-price');
	Route::get('/partner-product-purch-price/delete/{id}', 'PartnerProductPurchPriceController@delete')
				->name('partner-product-purch-price.delete')
				->middleware('can:delete-partner-product-purch-price');
	Route::get('/partner-product-purch-price/add', 'PartnerProductPurchPriceController@tambah')
				->name('partner-product-purch-price.tambah')
				->middleware('can:create-partner-product-purch-price');
	Route::post('/partner-product-purch-price/store', 'PartnerProductPurchPriceController@store')
				->name('partner-product-purch-price.store')
				->middleware('can:create-partner-product-purch-price');
	Route::get('/partner-product-purch-price/edit/{id}', 'PartnerProductPurchPriceController@edit')
				->name('partner-product-purch-price.edit')
				->middleware('can:update-partner-product-purch-price');
	Route::post('/partner-product-purch-price/update', 'PartnerProductPurchPriceController@update')
				->name('partner-product-purch-price.update')
				->middleware('can:update-partner-product-purch-price');
	Route::get('/partner-product-purch-price/ajax-get-product-partner/{partner?}/{provider?}', 'PartnerProductPurchPriceController@ajaxGetProductPartner')
				->name('partner-product-purch-price.ajaxGetProductPartner');
	Route::get('/partner-product-purch-price/yajra/getDatas/{request?}', 'PartnerProductPurchPriceController@yajraGetData')
				->name('partner-product-purch-price.yajra.getDatas');
	Route::get('partner-product-purch-price/upload', 'PartnerProductPurchPriceController@upload')
			  ->name('partner-product-purch-price.upload')
				->middleware('can:create-partner-product-purch-price');
	Route::post('partner-product-purch-price/template', 'PartnerProductPurchPriceController@template')
			  ->name('partner-product-purch-price.template')
				->middleware('can:create-partner-product-purch-price');
	Route::post('partner-product-purch-price/prosesTemplate', 'PartnerProductPurchPriceController@prosesTemplate')
			  ->name('partner-product-purch-price.prosesTemplate')
				->middleware('can:create-partner-product-purch-price');
	Route::post('partner-product-purch-price/storeTemplate', 'PartnerProductPurchPriceController@storeTemplate')
			  ->name('partner-product-purch-price.storeTemplate')
				->middleware('can:create-partner-product-purch-price');
// partner product purch price

// partner server
	Route::get('/partner-server', 'PartnerPulsaServerPropController@index')
				->name('partner-server.index')
				->middleware('can:read-partner-server');
	Route::post('/partner-server/store', 'PartnerPulsaServerPropController@store')
				->name('partner-server.store')
				->middleware('can:create-partner-server');
	Route::post('/partner-server/update', 'PartnerPulsaServerPropController@update')
				->name('partner-server.update')
				->middleware('can:update-partner-server');
	Route::get('/partner-server/delete/{id}', 'PartnerPulsaServerPropController@delete')
				->name('partner-server.delete')
				->middleware('can:delete-partner-server');
// partner server

//----- START PRODUCT -----//
	Route::get('product', 'ProductController@index')
				->name('product.index')
				->middleware('can:read-product');
	Route::get('product/add', 'ProductController@tambah')
				->name('product.tambah')
				->middleware('can:create-product');
	Route::post('product', 'ProductController@store')
				->name('product.store')
				->middleware('can:create-product');
	Route::get('product/edit/{product_code}', 'ProductController@ubah')
				->name('product.ubah')
				->middleware('can:update-product');
	Route::post('product/edit', 'ProductController@update')
				->name('product.update')
				->middleware('can:update-product');
	Route::get('product/active/{id}', 'ProductController@active')
				->name('product.active')
				->middleware('can:activate-product');
	Route::get('product/delete/{id}', 'ProductController@delete')
				->name('product.delete')
				->middleware('can:delete-product');
	Route::post('product/edit-sort-number/', 'ProductController@editSortNumber')
				->name('product.edit-sort-number')
				->middleware('can:sort-number-product');
	Route::get('/product/yajra/getDatas/{request?}', 'ProductController@yajraGetData')
				->name('product.yajra.getDatas');
//----- PRODUCT -----//

//----- START PRODUCT MLM-----//
	Route::get('product-mlm', 'ProductMlmController@index')
				->name('product-mlm.index')
				->middleware('can:read-product-mlm');
	Route::get('product-mlm/add', 'ProductMlmController@tambah')
				->name('product-mlm.tambah')
				->middleware('can:create-product-mlm');
	Route::post('product-mlm', 'ProductMlmController@store')
				->name('product-mlm.store')
				->middleware('can:create-product-mlm');
	Route::get('product-mlm/delete/{id}', 'ProductMlmController@delete')
				->name('product-mlm.delete')
				->middleware('can:delete-product-mlm');
	Route::get('/product-mlm/yajra/getDatas/{request?}', 'ProductMlmController@yajraGetData')
				->name('product-mlm.yajra.getDatas');
//----- PRODUCT MLM-----//				

//----- PRODUCT SELL PRICE -----//
	Route::get('product-sell-price', 'ProductSellPriceController@index')
			  ->name('product-sell-price.index')
				->middleware('can:read-product-sell-price');
	Route::get('product-sell-price/add', 'ProductSellPriceController@tambah')
			  ->name('product-sell-price.tambah')
				->middleware('can:create-product-sell-price');
	Route::get('product-sell-price/product/{id}', 'ProductSellPriceController@bindProduct')
			  ->name('product-sell-price.bindProduct');
	Route::post('product-sell-price', 'ProductSellPriceController@store')
			  ->name('product-sell-price.store')
				->middleware('can:create-product-sell-price');
	Route::get('product-sell-price/edit/{id}', 'ProductSellPriceController@ubah')
			  ->name('product-sell-price.ubah')
				->middleware('can:update-product-sell-price');
	Route::get('product-sell-price/add/{id}', 'ProductSellPriceController@ubah')
			  ->name('product-sell-price.addWithData')
				->middleware('can:create-product-sell-price');

	Route::post('product-sell-price/edit', 'ProductSellPriceController@update')
			  ->name('product-sell-price.edit')
				->middleware('can:update-product-sell-price');
	Route::get('product-sell-price/active/{id}', 'ProductSellPriceController@active')
			  ->name('product-sell-price.active')
				->middleware('can:activate-product-sell-price');
	Route::get('product-sell-price/delete/{id}', 'ProductSellPriceController@delete')
			  ->name('product-sell-price.delete')
				->middleware('can:delete-product-sell-price');
	Route::get('/product-sell-price/yajra/getDatas/{request?}', 'ProductSellPriceController@yajraGetData')
				->name('product-sell-price.yajra.getDatas');

	  //----- Upload Excel
	Route::get('product-sell-price/upload', 'ProductSellPriceController@upload')
			  ->name('product-sell-price.upload');
	Route::get('product-sell-price/upload/template', 'ProductSellPriceController@template')
			  ->name('product-sell-price.template');
	Route::post('product-sell-price/upload/template', 'ProductSellPriceController@prosesTemplate')
			  ->name('product-sell-price.prosesTemplate');
	Route::post('product-sell-price/upload/store-template', 'ProductSellPriceController@storeTemplate')
			  ->name('product-sell-price.storeTemplate');
//----- PRODUCT SELL PRICE -----//

//----- PRODUCT SELL PRICE MLM-----//
	Route::get('product-sell-price-mlm', 'ProductSellPriceMlmController@index')
			  ->name('product-sell-price-mlm.index')
				->middleware('can:read-product-sell-price-mlm');
	Route::get('product-sell-price-mlm/add', 'ProductSellPriceMlmController@tambah')
			  ->name('product-sell-price-mlm.tambah')
				->middleware('can:create-product-sell-price-mlm');
	Route::get('product-sell-price-mlm/product/{id}', 'ProductSellPriceMlmController@bindProduct')
			  ->name('product-sell-price-mlm.bindProduct');
	Route::post('product-sell-price-mlm', 'ProductSellPriceMlmController@store')
			  ->name('product-sell-price-mlm.store')
				->middleware('can:create-product-sell-price-mlm');

	/*Route::get('product-sell-price-mlm/edit/{id}', 'ProductSellPriceMlmController@ubah')
			  ->name('product-sell-price-mlm.ubah')
				->middleware('can:update-product-sell-price-mlm');*/
	Route::get('product-sell-price-mlm/add/{id}', 'ProductSellPriceMlmController@ubah')
			  ->name('product-sell-price-mlm.addWithData')
				->middleware('can:create-product-sell-price-mlm');
	
	Route::post('product-sell-price-mlm/edit', 'ProductSellPriceMlmController@update')
			  ->name('product-sell-price-mlm.edit')
				->middleware('can:update-product-sell-price-mlm');
	/*Route::get('product-sell-price-mlm/active/{id}', 'ProductSellPriceMlmController@active')
			  ->name('product-sell-price-mlm.active')
				->middleware('can:activate-product-sell-price-mlm');*/
	Route::get('product-sell-price-mlm/delete/{id}', 'ProductSellPriceMlmController@delete')
			  ->name('product-sell-price-mlm.delete')
				->middleware('can:delete-product-sell-price-mlm');
	Route::get('/product-sell-price-mlm/yajra/getDatas/{request?}', 'ProductSellPriceMlmController@yajraGetData')
				->name('product-sell-price-mlm.yajra.getDatas');

		  //----- Upload Excel
	Route::get('product-sell-price-mlm/upload', 'ProductSellPriceMlmController@upload')
			  ->name('product-sell-price-mlm.upload');
	Route::get('product-sell-price-mlm/upload/template', 'ProductSellPriceMlmController@template')
			  ->name('product-sell-price-mlm.template');
	Route::post('product-sell-price-mlm/upload/template', 'ProductSellPriceMlmController@prosesTemplate')
			  ->name('product-sell-price-mlm.prosesTemplate');
	Route::post('product-sell-price-mlm/upload/store-template', 'ProductSellPriceMlmController@storeTemplate')
			  ->name('product-sell-price-mlm.storeTemplate');			  
//----- PRODUCT SELL PRICE MLM-----//


//----- Management Account -----//
	Route::get('account', 'AccountController@index')->name('account.index')->middleware('can:read-user');
	Route::get('account/add', 'AccountController@tambah')->name('account.tambah')->middleware('can:create-user');
	Route::post('account/add', 'AccountController@store')->name('account.store')->middleware('can:create-user');
	Route::get('account/edit/{id}', 'AccountController@ubah')->name('account.ubah')->middleware('can:update-user');
	Route::post('account/edit', 'AccountController@update')->name('account.update')->middleware('can:update-user');

	Route::get('account/role', 'AccountController@role')->name('account.role')->middleware('can:read-role');
	Route::get('account/role/create', 'AccountController@roleCreate')->name('account.roleCreate')->middleware('can:create-role');
	Route::post('account/role/create', 'AccountController@rolePost')->name('account.rolePost')->middleware('can:create-role');
	Route::get('account/role/{slug}', 'AccountController@roleUbah')->name('account.roleUbah')->middleware('can:update-role');
	Route::post('account/role/update', 'AccountController@roleEdit')->name('account.roleEdit')->middleware('can:update-role');

	Route::get('account/reset/{id}', 'AccountController@reset')->middleware('can:reset-user');
	Route::get('account/actived/{id}', 'AccountController@activate')->middleware('can:activate-user');
	Route::get('account/profile', 'AccountController@profile')->name('account.profile');
	Route::post('account/profile', 'AccountController@postProfile')->name('account.postProfile');
	Route::post('account/profile/password', 'AccountController@changePassword')->name('account.password');
//----- Management Account -----//

//------- Deposit Agent -------//
	Route::get('deposit-agent-confirm', 'DepositAgentController@indexConfirm')
				->name('deposit-agent-confirm.index')
				->middleware('can:read-deposit-confirm');
	Route::post('deposit-agent-confirm', 'DepositAgentController@getUniqueCode')
				->name('deposit-agent-confirm.getUniqueCode');
	Route::post('deposit-agent-proses-confirm', 'DepositAgentController@confirm')
				->name('deposit-agent.confirm')
				->middleware('can:confirm-deposit-confirm');
	Route::get('getUnconfirmedUniqueCodes', 'DepositAgentController@getUnconfirmedUniqueCodes');


	Route::get('deposit-agent-void', 'DepositAgentController@void')
				->name('deposit-agent-reversal.index')
				->middleware('can:read-deposit-reversal');
	Route::post('deposit-agent-void', 'DepositAgentController@getRangeDate')
				->name('deposit-agent-reversal.getRangeDate');
	Route::post('deposit-agent-void/reversal', 'DepositAgentController@reversalTrx')
				->name('deposit-agent-reversal.reversalTrx')
				->middleware('can:confirm-deposit-reversal');
	Route::get('getConfirmedTopUp', 'DepositAgentController@getConfirmedTopUp');


	Route::get('deposit-agent-unconfirm', 'DepositAgentController@indexUnconfirm')
				->name('deposit-agent-unconfirm.index');
	Route::post('deposit-agent-proses-unconfirm', 'DepositAgentController@postUnconfirm')
				->name('deposit-agent-unconfirm.postUnconfirm');

	Route::get('inquiry-mutasi-rekening-mandiri', 'InquiryMutasiRekeningMandiriController@index')
				->name('inquiry-mutasi-rekening-mandiri.index')
				->middleware('can:read-inquiry-mutasi-rekening-mandiri');
	Route::get('inquiry-mutasi-rekening-mandiri/getDatas', 'InquiryMutasiRekeningMandiriController@getMutasiRekeningMandiriList')
				->name('inquiry-mutasi-rekening-mandiri.getDatas')
				->middleware('can:read-inquiry-mutasi-rekening-mandiri');
//------- Deposit Agent -------//

//-------- Sales Deposit------------//
	//salesman
	Route::get('salesman', 'SalesmanController@index')
			->name('salesman.index')
			->middleware('can:read-salesman');
	Route::get('salesman/getDatas', 'SalesmanController@getSalesmanList')
			->name('salesman.getDatas')
			->middleware('can:read-salesman');
	Route::get('salesman/add', 'SalesmanController@add')
			->name('salesman.add')
			->middleware('can:create-salesman');
	Route::post('salesman/store', 'SalesmanController@store')
			->name('salesman.store')
			->middleware('can:create-salesman');
	Route::get('salesman/edit/{id}', 'SalesmanController@edit')
			->name('salesman.edit')
			->middleware('can:update-salesman');
	Route::post('salesman/update', 'SalesmanController@update')
			->name('salesman.update')
			->middleware('can:update-salesman');
	Route::get('salesman/actived/{id}/{version}/{status}', 'SalesmanController@active')
			->name('salesman.active')
			->middleware('can:activate-salesman');


	//sales deposit transaction
	Route::get('sales-deposit-transaction', 'SalesDepositController@index')
			->name('salesDepositTransaction.index')
			->middleware('can:read-sales-deposit-transaction');
	Route::get('sales-deposit-transaction/getDatas', 'SalesDepositController@getSalesDepositTransactionList')
			->name('salesDepositTransaction.getDatas')
			->middleware('can:read-sales-deposit-transaction');
	Route::get('sales-deposit-transaction/set-sudah-setor/{id}/{version}', 'SalesDepositController@setSudahSetor')
			->name('salesDepositTransaction.setSudahSetor')
			->middleware('can:set-sudah-setor-sales-deposit-transaction');
	Route::get('sales-deposit-transaction/set-belum-setor/{id}/{version}', 'SalesDepositController@setBelumSetor')
			->name('salesDepositTransaction.setBelumSetor')
			->middleware('can:set-belum-setor-sales-deposit-transaction');
	Route::get('sales-deposit-transaction/detail/{id}', 'SalesDepositController@detail')
			->name('salesDepositTransaction.detail')
			->middleware('can:read-sales-deposit-transaction');
	/*Route::get('sales-deposit-transaction/detailList/{id}', 'SalesDepositController@getSalesDepositTransactionDetailList')
			->name('salesDepositTransaction.getDetailList')
			->middleware('can:read-sales-deposit-transaction');*/
//-------- Sales Deposit------------//		

//------- Report -------//
	Route::get('report-supplier-pkp', 'ReportController@bySupplierPkp')->name('report.bySupplierPkp')->middleware('can:report-supplier-pkp');
	Route::post('report-supplier-pkp', 'ReportController@postBySupplierPkp')->name('report.postBySupplierPkp');
	
	Route::get('report-supplier-non-pkp', 'ReportController@bySupplierNonPkp')->name('report.bySupplierNonPkp')->middleware('can:report-supplier-non-pkp');
	Route::post('report-supplier-non-pkp', 'ReportController@postBySupplierNonPkp')->name('report.postBySupplierNonPkp');

	Route::get('report-agent', 'ReportController@byAgent')->name('report.byAgent')->middleware('can:report-agent');
	Route::post('report-agent', 'ReportController@postByAgent')->name('report.postByAgent');
	
	Route::get('report-agent-mlm', 'ReportController@byAgentMlm')->name('report.byAgentMlm')->middleware('can:report-agent-mlm');
	Route::post('report-agent-mlm', 'ReportController@postByAgentMlm')->name('report.postByAgentMlm');

	Route::get('report-provider', 'ReportController@byProvider')->name('report.byProvider')->middleware('can:report-provider');
	Route::post('report-provider', 'ReportController@postByProvider')->name('report.postByProvider');
	
	Route::get('report-topup-deposit-partner', 'ReportController@byTopUpDepositPartner')->name('report.byTopUpDepositPartner')->middleware('can:report-topup-deposit-partner');
	Route::get('report-topup-deposit-partner/generate', 'ReportController@postByTopUpDepositPartner')->name('report.postByTopUpDepositPartner');

	Route::get('report-deposit-harian-agent', 'ReportController@byDepositHarianAgent')->name('report.byDepositHarianAgent')->middleware('can:report-deposit-harian-agent');
	Route::post('report-deposit-harian-agent/generate', 'ReportController@postByDepositHarianAgent')->name('report.postByDepositHarianAgent');
	
	Route::get('inquiry-pesanan-agent', 'InquiryPesananAgentController@index')
				->name('report.inquiry-pesanan-agent-index')->middleware('can:report-inquiry-agent');
	Route::get('inquiry-pesanan-agent/getDatas', 'InquiryPesananAgentController@getMemberOrderProductList')
				->name('report.inquiry-pesanan-agent-get-datas')->middleware('can:report-inquiry-agent');
	Route::get('inquiry-pesanan-agent/log', 'InquiryPesananAgentController@getLogTransactionList')
				->name('report.inquiry-pesanan-agent-log')->middleware('can:report-inquiry-agent');
	Route::post('inquiry-pesanan-agent/set-sukses', 'InquiryPesananAgentController@setSukses')
				->name('report.inquiry-pesanan-agent-set-sukses')
				->middleware('can:set-sukses-inquiry-pesanan-agent');
	Route::post('inquiry-pesanan-agent/set-gagal', 'InquiryPesananAgentController@setGagal')
				->name('report.inquiry-pesanan-agent-set-gagal')
				->middleware('can:set-gagal-inquiry-pesanan-agent');
	Route::get('inquiry-pesanan-agent/detil-change-status-transaction', 'InquiryPesananAgentController@getDetailChangeStatusTransaction')
				->name('report.inquiry-pesanan-agent-detail-change-status-transaction')
				->middleware('can:report-inquiry-agent');

	Route::get('rekap-sales-harian-agent', 'ReportController@byRekapSalesHarianAgent')->name('report.byRekapSalesHarianAgent')->middleware('can:report-rekap-sales-harian-agent');
	Route::post('rekap-sales-harian-agent/generate', 'ReportController@postByRekapSalesHarianAgent')->name('report.postByRekapSalesHarianAgent');

	Route::get('weekly-sales-summary', 'ReportController@byWeeklySalesSummary')->name('report.byWeeklySalesSummary')->middleware('can:report-weekly-sales-summary');
	Route::post('weekly-sales-summary/generate', 'ReportController@postByWeeklySalesSummary')->name('report.postByWeeklySalesSummary');

	Route::get('saldo-deposit-agent', 'ReportController@bySaldoDepositAgent')->name('report.bySaldoDepositAgent')->middleware('can:report-saldo-deposit-agent');
	Route::post('saldo-deposit-agent/generate', 'ReportController@postBySaldoDepositAgent')->name('report.postBySaldoDepositAgent');

	Route::get('sales-deposit', 'ReportController@bySalesDeposit')->name('report.bySalesDeposit')->middleware('can:report-sales-deposit');
	Route::post('sales-deposit/generate', 'ReportController@postBySalesDeposit')->name('report.postBySalesDeposit');	

	Route::get('report-data-agent-not-active', 'ReportController@byDataAgentNotActive')->name('report.byDataAgentNotActive')->middleware('can:report-data-agent-not-active');
	Route::post('report-data-agent-not-active/generate', 'ReportController@postByDataAgentNotActive')->name('report.postByDataAgentNotActive');

	Route::get('agent-member-paloma', 'ReportController@byAgentMemberPaloma')->name('report.byAgentMemberPaloma')->middleware('can:report-agent-member-paloma');
	Route::post('agent-member-paloma/generate', 'ReportController@postByAgentMemberPaloma')->name('report.postByAgentMemberPaloma');

	Route::get('report-statistik-transaksi-error', 'ReportController@byStatistikTransaksiError')
			->name('report.byStatistikTransaksiError')
			->middleware('can:report-statistik-transaksi-error');
	Route::post('report-statistik-transaksi-error', 'ReportController@postByStatistikTransaksiError')
			->name('report.postByStatistikTransaksiError');

	Route::get('report-perubahan-status-manual', 'ReportController@byPerubahanStatusManual')
			->name('report.byPerubahanStatusManual')
			->middleware('can:report-perubahan-status-manual');
	Route::post('report-perubahan-status-manual', 'ReportController@postByPerubahanStatusManual')
			->name('report.postByPerubahanStatusManual');
//------- Report -------//


