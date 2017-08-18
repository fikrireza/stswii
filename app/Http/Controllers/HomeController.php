<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Provider;
use App\Models\ProviderPrefix;
use App\Models\Product;
use App\Models\PartnerPulsa;
use App\Models\PartnerProduct;
use App\Models\Agent;

use Auth;
use DB;
use Validator;

class HomeController extends Controller
{

		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
				$this->middleware('auth');
		}

		public function index()
		{
			$provider = Provider::get();
			$providerPrefix = ProviderPrefix::get();
			$product = Product::get();
			$partnerPulsa = PartnerPulsa::get();
			$partnerProduct = PartnerProduct::get();
			$agent = Agent::get();

    	return view('home.index', compact('provider','providerPrefix','product','partnerPulsa','partnerProduct','agent'));

	  }
}
