<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductSellPrice;

use Auth;
use DB;
use Validator;

class HomeController extends Controller{

	public function login()
	{
		return view('auth.login');
	}

	public function index(){
    	$ProductSellPrice = ProductSellPrice::get();

    	return view('home.index', compact(
			'ProductSellPrice'
		));
    }
}
