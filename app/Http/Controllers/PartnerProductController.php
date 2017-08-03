<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PartnerProduct;
use App\Models\PartnerPulsa;
use App\Models\Provider;
use App\Models\Product;

use Auth;
use DB;
use Validator;
use Carbon\Carbon;

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

    public function index(){
        $getPartnerProduct = PartnerProduct::select(
            'partner_pulsa_id',
            'provider_id',
            'product_id',
            'partner_product_id',
            'partner_product_code',
            'partner_product_name',
            'active',
            'version'
          )
          ->get();

      	return view('partner-product.index', compact('getPartnerProduct'));
    }

    public function create(){

      $tahun = date('y');
      $bulan = date('m');
      $rand = rand(1000,9999);

      $partner_product_code = 'SPT-'.$tahun.'-'.$bulan.'-'.$rand;

      $cek_kode = PartnerProduct::where('partner_product_code', $partner_product_code)->first();

      if(!$cek_kode){
        $partner_product_code;
      }
      else{
        $partner_product_code = 'Partner Pulsa Code is Empty - Please Contact Amadeo';
      }

      $getPartner = PartnerPulsa::select(
          'partner_pulsa_id',
          'partner_pulsa_code',
          'partner_pulsa_name'
        )
        // ->where('active', 1)
        ->get();

      $getProvider = Provider::select(
          'provider_id',
          'provider_code',
          'provider_name'
        )
        ->get();

    	return view('partner-product.tambah', compact(
          'partner_product_code',
          'getPartner',
          'getProvider'
        ));
    }

    public function store(Request $request){
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
  			'partner_product_code' => 'required|unique:sw_partner_product',
  			'partner_product_name' => 'required',
        'product_id' => 'required',
        'provider_id' => 'required',
  			'partner_pulsa_id' => 'required',
  		], $message);

  		if($validator->fails()){
  			return redirect()->route('partner-product.create')
  				->withErrors($validator)->withInput();
  		}

  		DB::transaction(function () use($request) {
  			$save = new PartnerProduct;

  			$save->partner_product_code = $request->partner_product_code;
  			$save->partner_product_name = $request->partner_product_name;
  			$save->partner_pulsa_id     = $request->partner_pulsa_id;
  			$save->provider_id          = $request->provider_id;
  			$save->product_id           = $request->product_id;

  			$save->active               = /*isset($request->active) ? */1 /*: 0*/;
  			$save->active_datetime     = Carbon::now()->format('YmdHis');
        $save->non_active_datetime = '';
        $save->version             = 0;
        $save->create_user_id      = Auth::user()->id;
        $save->create_datetime     = Carbon::now()->format('YmdHis');
        $save->update_user_id      = 0;/*Auth::user()->id*/
        $save->update_datetime     = '';
  			$save->save();
  		});

  		return redirect()->route('partner-product.index')
        ->with('alret', 'alert-success')
  			->with('berhasil', 'Berhasil Menambahkan Partner Product '.$request->partner_product_name);
    }

    public function edit($id, $version){
      $getPartnerProduct = PartnerProduct::find($id);

      if($getPartnerProduct == null){
        $retrun = 'index';
        $info = 'Data Partner Produk gagal diubah! Tidak dapat menemukan Partner Produk!';
        $alret = 'alert-danger';
      }
      else if($getPartnerProduct->version != $version){
        $retrun = 'index';
        $User = User::find($getPartnerProduct->update_user_id);
        $info = 'Data Partner Produk gagal diubah! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $retrun = 'update';
      	$getPartner = PartnerPulsa::select(
            'partner_pulsa_id',
            'partner_pulsa_code',
            'partner_pulsa_name'
          )
          // ->where('active', 1)
          ->get();

        $getProvider = Provider::select(
            'provider_id',
            'provider_code',
            'provider_name'
          )
          ->get();

        $getProduct = Product::select(
            'product_id',
            'product_code',
            'product_name'
          )
          ->where('provider_id', $getPartnerProduct->provider_id)
          ->get();
      }

      if($retrun == 'index'){
        return redirect()->route('partner-product.index')
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }
      else if($retrun == 'update'){
        return view('partner-product.ubah', compact(
            'getPartnerProduct',
            'getPartner',
            'getProvider',
            'getProduct'
          ));
      }
    }

    public function update($id, $version, Request $request){
      $message = [
  			'partner_product_code.required' => 'mohon isi',
  			'partner_product_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
  			'partner_product_name.required' => 'mohon isi',
  			'partner_pulsa_id.required' => 'mohon isi',
  			'provider_id.required' => 'mohon isi',
  			'product_id.required' => 'mohon isi',
  		];

  		$validator = Validator::make($request->all(), [
  			'partner_product_code' => 'required',
  			'partner_product_name' => 'required',
  			'partner_pulsa_id' => 'required',
  			'provider_id' => 'required',
  			'product_id' => 'required',
  		], $message);

  		if($validator->fails()){
  			return redirect()->route('partner-product.edit', ['id' => $id, 'version' => $version])
  				->withErrors($validator)->withInput();
  		}

      $update = PartnerProduct::find($request->partner_product_id);

      if($update == null){
        $info = 'Data Partner Produk gagal diubah! Tidak dapat menemukan Partner Produk!';
        $alret = 'alert-danger';
      }
      else if($update->version != $request->version){
        $User = User::find($update->update_user_id);
        $info = 'Data Partner Produk gagal diubah! Data Partner Produk telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil Memperbarui Data Partner Produk '.$update->partner_pulsa_name;
        $alret = 'alert-success';
        DB::transaction(function () use($update, $request) {
          $update->increment('version');
    			$update->partner_product_name = $request->partner_product_name;
    			$update->partner_pulsa_id     = $request->partner_pulsa_id;
    			$update->provider_id          = $request->provider_id;
    			$update->product_id           = $request->product_id;
          $update->update_user_id     = Auth::user()->id;
          $update->update_datetime    = Carbon::now()->format('YmdHis');
          $update->update();
        });
      }

      return redirect()->route('partner-product.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function active($id, $version, $status){
      $getPartnerProduct = PartnerProduct::find($id);

      if($getPartnerProduct == null){
        $info = 'Status Partner Produk gagal diubah! Tidak dapat menemukan Partner Produk!';
        $alret = 'alert-danger';
      }
      else if($getPartnerProduct->version != $version){
        $User = User::find($getPartnerProduct->update_user_id);
        $info = 'Status Partner Produk gagal diubah! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil Memperbarui Status Partner Produk '.$getPartnerProduct->partner_product_name;
        $alret = 'alert-success';
        DB::transaction(function () use($getPartnerProduct, $status) {
          $getPartnerProduct->increment('version');
          $getPartnerProduct->active         = $status;
          $getPartnerProduct->update_user_id = Auth::user()->id;
          $getPartnerProduct->update_datetime= Carbon::now()->format('YmdHis');
          if($status == 1){
            $getPartnerProduct->active_datetime  = Carbon::now()->format('YmdHis');
          }
          else if($status == 0){
            $getPartnerProduct->non_active_datetime  = Carbon::now()->format('YmdHis');
          }
          $getPartnerProduct->update();
        });
      }
      return redirect()->route('partner-product.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function delete($id, $version){
    	$getPartnerProduct = PartnerProduct::find($id);

      if($getPartnerProduct == null){
        $info = 'Data Partner Produk gagal dihapus! Tidak dapat menemukan Partner Produk!';
        $alret = 'alert-danger';
      }
      else if($getPartnerProduct->version != $version){
        $User = User::find($getPartnerProduct->update_user_id);
        $info = 'Data Partner Produk gagal dihapus! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil hapus Data Partner Produk ';
        $alret = 'alert-success';
        $getPartnerProduct->delete();
      }
      return redirect()->route('partner-product.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function ajaxGetProductList($id=0){
    	$getProduct = Product::where('provider_id', $id)->get();

    	return $getProduct;
    }
}
