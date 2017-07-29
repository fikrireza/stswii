<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PartnerProduct;
use App\Models\PartnerPulsa;
use App\Models\Provider;
use App\Models\Product;

use Auth;
use DB;
use Validator;

class PartnerProductController extends Controller
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

      	return view('partner-product.index');
    }

    public function create()
    {
    	// $getPartnerPulsa = PartnerPulsa::all();
    	// $getProvider     = Provider::all();
    	// $getProduct      = Product::all();

        $tahun = date('y');
        $bulan = date('m');
        $rand = rand(1000,9999);

        $partner_product_code = 'SPT-'.$tahun.'-'.$bulan.'-'.$rand;

        // $cek_kode = PartnerProduct::where('partner_product_code', $partner_product_code)->first();

        // if(!$cek_kode){
        //   $partner_product_code;
        // }else{
        //   $partner_product_code = 'Partner Pulsa Code is Empty - Please Contact Amadeo';
        // }

    	return view('partner-product.tambah', compact('partner_product_code'));
    }

    public function store(Request $request)
    {
      $message = [
  			'partner_product_code.required' => 'mohon isi',
  			'partner_product_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
  			'partner_product_code.unique' => 'Produk ini sudah ada',
  			'partner_product_name.required' => 'mohon isi',
  			'partner_pulsa_id.required' => 'mohon isi',
  			'provider_id.required' => 'mohon isi',
  			'product_id.required' => 'mohon isi',
  		];

  		$validator = Validator::make($request->all(), [
  			'partner_product_code' => 'required|unique:amd_partner_products',
  			'partner_product_name' => 'required',
  			'partner_pulsa_id' => 'required',
  			'provider_id' => 'required',
  			'product_id' => 'required',
  		], $message);

  		if($validator->fails()){
  			return redirect()->route('partner-product.create')
  				->withErrors($validator);
  		}

  		DB::transaction(function () use($request) {
  			$save = new PartnerProduct;

  			$save->partner_product_code = $request->partner_product_code;
  			$save->partner_product_name = $request->partner_product_name;
  			$save->partner_pulsa_id     = $request->partner_pulsa_id;
  			$save->provider_id          = $request->provider_id;
  			$save->product_id           = $request->product_id;

  			$save->active               = isset($request->active) ? 1 : 0;
  			$save->active_datetime      = date('Y-m-d H:i:s');
  			$save->non_active_datetime  = date('Y-m-d H:i:s');

  			$save->version 		        = '1';
  			$save->create_user_id	    = '1'/*Auth::user()->id*/;
  			$save->update_user_id	    = '1'/*Auth::user()->id*/;
  			$save->save();
  		});

  		return redirect()->route('partner-product.index')
  			->with('berhasil', 'Berhasil Menambahkan Partner Product '.$request->partner_product_name);
    }

    public function edit($id)
    {
    	// $getPartnerProduct = PartnerProduct::find($id);
      //
    	// $getPartnerPulsa = PartnerPulsa::all();
    	// $getProvider     = Provider::all();
    	// $getProduct      = Product::where('provider_id', $getPartnerProduct->provider_id )->get();
      //
    	// if(!$getPartnerProduct){
      //     return view('errors.404');
      //   }

    	return view('partner-product.ubah')->with(compact('getPartnerProduct', 'getPartnerPulsa', 'getProvider', 'getProduct'));
    }

    public function update($id, Request $request)
    {
      $message = [
  			'partner_product_code.required' => 'mohon isi',
  			'partner_product_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
  			'partner_product_code.unique' => 'Produk ini sudah ada',
  			'partner_product_name.required' => 'mohon isi',
  			'partner_pulsa_id.required' => 'mohon isi',
  			'provider_id.required' => 'mohon isi',
  			'product_id.required' => 'mohon isi',
  		];

  		$validator = Validator::make($request->all(), [
  			'partner_product_code' => 'required|unique:amd_partner_products,partner_product_code,'.$id,
  			'partner_product_name' => 'required',
  			'partner_pulsa_id' => 'required',
  			'provider_id' => 'required',
  			'product_id' => 'required',
  		], $message);

  		if($validator->fails()){
  			return redirect()->route('partner-product.edit', ['id' => $id])
  				->withErrors($validator);
  		}

  		DB::transaction(function () use($request, $id) {
  			$save = PartnerProduct::find($id);

  			$save->partner_product_code = $request->partner_product_code;
  			$save->partner_product_name = $request->partner_product_name;
  			$save->partner_pulsa_id     = $request->partner_pulsa_id;
  			$save->provider_id          = $request->provider_id;
  			$save->product_id           = $request->product_id;

  			$save->active              = isset($request->active) ? 1 : 0;
  			if(isset($request->active))
  			{
  				$save->active_datetime     = date('Y-m-d H:i:s');
  			}
  			else
  			{
  				$save->non_active_datetime = date('Y-m-d H:i:s');
  			}

  			$save->version 		       = '1';
  			$save->create_user_id	   = '1'/*Auth::user()->id*/;
  			$save->update_user_id	   = '1'/*Auth::user()->id*/;
  			$save->save();
  		});

  		return redirect()->route('partner-product.index')
  			->with('berhasil', 'Berhasil Mengubah Partner Product '.$request->partner_product_name);
    }

    public function active($id)
    {
        $getPartnerProduct = PartnerProduct::find($id);

        if(!$getPartnerProduct){
          return view('errors.404');
        }

        if ($getPartnerProduct->active == 1) {
          $getPartnerProduct->active = 0;
          $getPartnerProduct->non_active_datetime = date('Y-m-d H:i:s');
          $getPartnerProduct->update_user_id = 1; //Auth::user()->id;
          $getPartnerProduct->update();

          return redirect()->route('partner-product.index')->with('berhasil', 'Successfully Nonactive'.$getPartnerProduct->partner_product_name);
        }else{
          $getPartnerProduct->active = 1;
          $getPartnerProduct->active_datetime = date('Y-m-d H:i:s');
          $getPartnerProduct->update_user_id = 1; //Auth::user()->id;
          $getPartnerProduct->update();

          return redirect()->route('partner-product.index')->with('berhasil', 'Successfully Activated '.$getPartnerProduct->partner_product_name);
        }
    }

    public function delete($id)
    {
    	$getPartnerProduct = PartnerProduct::find($id);

    	if(!$getPartnerProduct){
          return view('errors.404');
        }

  		$getPartnerProduct->delete();
  		return redirect()->route('partner-product.index')
  			->with('berhasil', 'Berhasil menghapus Partner Pulsa ');
    }

    public function ajaxGetProductList($id=0)
    {
    	$getProduct = Product::where('provider_id', $id)->get();

    	return $getProduct;
    }
}
