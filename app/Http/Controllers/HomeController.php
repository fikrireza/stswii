<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductSellPrice;

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

			$ProductSellPrice = ProductSellPrice::get();

    	return view('home.index', compact('ProductSellPrice'));

	  }
}
