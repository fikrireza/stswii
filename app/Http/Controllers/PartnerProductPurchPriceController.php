<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PartnerProductPurchPrice;
use App\Models\PartnerProduct;
use App\Models\PartnerPulsa;
use App\Models\Provider;

use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class PartnerProductPurchPriceController extends Controller
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
        $getPPPP = PartnerProductPurchPrice::select(
            'partner_product_id',
            'partner_product_purch_price_id',
            'gross_purch_price',
            'flg_tax',
            'tax_percentage',
            'datetime_start',
            'datetime_end',
            'active',
            'version'
          )
          ->get();

        return view('partner-product-purchase-price.index', compact('getPPPP'));
    }

    public function active($id, $version, $status){
      $findData = PartnerProductPurchPrice::find($id);

      if($findData == null){
        $info = 'Status Partner Produk Price gagal diubah! Tidak dapat menemukan Partner Produk Price!';
        $alret = 'alert-danger';
      }
      else if($findData->version != $version){
        $User = User::find($findData->update_user_id);
        $info = 'Status Partner Produk Price gagal diubah! Data Partner Produk Price telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else if($status == 1){
        if (date('YmdHis',strtotime($findData->datetime_end)) < Carbon::now()->format('YmdHis')) {
          $info  = 'Status Partner Produk Price gagal diubah! Periode Telah Lewat Tidak Dapat Diaktifkan!';
          $alret = 'alert-danger';
        }
        else{
          $checkData = PartnerProductPurchPrice::where('partner_product_id',$findData->partner_product_id)
            ->where('active',1)
            ->get();

          if(count($checkData) != 0){
            foreach ($checkData as $list){
              if(
                  date('YmdHis', strtotime($findData->datetime_start)) >= date('YmdHis', strtotime($list->datetime_start)) && 
                  date('YmdHis', strtotime($findData->datetime_start)) <= date('YmdHis', strtotime($list->datetime_end))
                ){ 
                $retrun= 'update';
                $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
                $alret = 'alert-danger';
                break;
              }
              else if(
                date('YmdHis', strtotime($findData->datetime_start)) <= date('YmdHis', strtotime($list->datetime_start)) && 
                date('YmdHis', strtotime($findData->datetime_end))  >= date('YmdHis', strtotime($list->datetime_end))
              ){
                $retrun= 'update';
                $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
                $alret = 'alert-danger';
                break;
              }
              else if(
                date('YmdHis', strtotime($findData->datetime_end)) >= date('YmdHis', strtotime($list->datetime_start)) && 
                date('YmdHis', strtotime($findData->datetime_end)) <= date('YmdHis', strtotime($list->datetime_end))
              ){
                $retrun= 'update';
                $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
                $alret = 'alert-danger';
                break;
              }
            }
          }
        }
      }

      if(!isset($alret)){
        $alret = 'alert-success';
        $info = 'Berhasil Memperbarui Status Partner Produk '.$findData->partner_product_name;
        DB::transaction(function () use($findData, $status) {
          $findData->increment('version');
          $findData->active         = $status;
          $findData->update_user_id = Auth::id();
          $findData->update_datetime= Carbon::now()->format('YmdHis');
          if($status == 1){
            $findData->active_datetime  = Carbon::now()->format('YmdHis');
          }
          else if($status == 0){
            $findData->non_active_datetime  = Carbon::now()->format('YmdHis');
          }
          $findData->update();
        });
      }
      
      return redirect()->route('partner-product-purch-price.index')
      ->with('alret', $alret)
      ->with('berhasil', $info);
    }

    public function delete($id, $version){
      $findData = PartnerProductPurchPrice::find($id);

      if($findData == null){
        $info = 'Data Partner Produk Purchase Price gagal dihapus! Tidak dapat menemukan Partner Produk Purchase Price!';
        $alret = 'alert-danger';
      }
      else if($findData->version != $version){
        $User = User::find($findData->update_user_id);
        $info = 'Data Partner Produk Purchase Price gagal dihapus! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil hapus Data Partner Produk Purchase Price';
        $alret = 'alert-success';
        $findData->delete();
      }
      return redirect()->route('partner-product-purch-price.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function tambah(){

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

      return view('partner-product-purchase-price.tambah',compact(
          'getPartner',
          'getProvider'
        ));
    }

    public function store(Request $request){
      $message = [
        'partner_product_id.required' => 'This field is required.',
        'gross_purch_price.required' => 'This field is required.',
        'gross_purch_price.numeric' => 'Numeric Only.',
        'datetime_start.required' => 'This field is required.',
        'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
        'datetime_end.required' => 'This field is required.',
        'tax_percentage.required_if'  => 'Tax Percentage required',
      ];

      $validator = Validator::make($request->all(), [
        'partner_product_id' => 'required',
        'gross_purch_price' => 'required|numeric',
        'datetime_start' => 'required|before_or_equal:datetime_end',
        'datetime_end' => 'required',
        'tax_percentage'      => 'required_if:flg_tax,1',
      ], $message);

      if($validator->fails()){
        return redirect()->route('partner-product-purch-price.tambah')->withErrors($validator)->withInput();
      }

      // cek jika periode sudah lewat atau belum
      if (date('YmdHis',strtotime($request->datetime_end)) < Carbon::now()->format('YmdHis')) {
        $active = 0;
      }
      else{
        $active = 1; 
        $checkData = PartnerProductPurchPrice::where('partner_product_id',$request->partner_product_id)
          ->where('active',1)
          ->get();
        if(count($checkData) != 0){
          foreach ($checkData as $list){
            if(
                date('YmdHis', strtotime($request->datetime_start)) >= date('YmdHis', strtotime($list->datetime_start)) && 
                date('YmdHis', strtotime($request->datetime_start)) <= date('YmdHis', strtotime($list->datetime_end))
              ){ 
              $retrun= 'update';
              $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
              $alret = 'alert-danger';
              break;
            }
            else if(
              date('YmdHis', strtotime($request->datetime_start)) <= date('YmdHis', strtotime($list->datetime_start)) && 
              date('YmdHis', strtotime($request->datetime_end))  >= date('YmdHis', strtotime($list->datetime_end))
            ){
              $retrun= 'update';
              $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
              $alret = 'alert-danger';
              break;
            }
            else if(
              date('YmdHis', strtotime($request->datetime_end)) >= date('YmdHis', strtotime($list->datetime_start)) && 
              date('YmdHis', strtotime($request->datetime_end)) <= date('YmdHis', strtotime($list->datetime_end))
            ){
              $retrun= 'update';
              $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
              $alret = 'alert-danger';
              break;
            }
          }
        }
      }

      if(!isset($alret)){
        $alret = 'alert-success';
        $info  = 'Data Partner Produk Price tersimpan!';
        DB::transaction(function() use($request, $active){
          $save = New PartnerProductPurchPrice;
          $save->partner_product_id = $request->partner_product_id;
          $save->gross_purch_price  = $request->gross_purch_price;
          $save->datetime_start     = date('YmdHis',strtotime($request->datetime_start));
          $save->datetime_end       = date('YmdHis',strtotime($request->datetime_end));
          $save->flg_tax            = $request->flg_tax == null ? 0 : 1;
          $save->tax_percentage     = $request->flg_tax == null ? 0 : $request->tax_percentage;
          $save->active             = $active;
          $save->active_datetime    = $active == 1 ? Carbon::now()->format('YmdHis') : '00000000000000';
          $save->non_active_datetime= $active == 0 ? Carbon::now()->format('YmdHis') : '00000000000000';
          $save->version            = 0;
          $save->create_user_id     = Auth::id();
          $save->create_datetime    = Carbon::now()->format('YmdHis');
          $save->update_user_id     = -99;
          $save->update_datetime    = '00000000000000';
          $save->save();
        });
      }

      if($alret == 'alert-danger'){
        return redirect()->route('partner-product-purch-price.tambah')
          ->withInput()
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }      
      else if($alret == 'alert-success'){
        return redirect()->route('partner-product-purch-price.tambah')
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }
    }

    public function edit($id, $version){

      $getPartnerProductPurchPrice = PartnerProductPurchPrice::find($id);

      if($getPartnerProductPurchPrice == null){
        $retrun = 'index';
        $info = 'Data Partner Produk Purchase Price Tidak dapat ditemukan!';
        $alret = 'alert-danger';
      }
      else if($getPartnerProductPurchPrice->version != $version){
        $retrun = 'index';
        $User = User::find($getPartnerProductPurchPrice->update_user_id);
        $info = 'Data Partner Produk Purchase Price Tidak dapat ditemukan! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
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

        $findPartnerProduct = PartnerProduct::find($getPartnerProductPurchPrice->partner_product_id);

        $getPartnerProduct = PartnerProduct::select(
          'partner_product_id',
          'partner_product_code',
          'partner_product_name'
        )
        ->where('partner_pulsa_id', $findPartnerProduct->partner_pulsa_id)
        ->where('provider_id', $findPartnerProduct->provider_id)
        ->get();

        // dd($getPartnerProduct);
      }

      if($retrun == 'index'){
        return redirect()->route('partner-product-purch-price.index')
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }
      else if($retrun == 'update'){
        return view('partner-product-purchase-price.ubah',compact(
            'getPartner',
            'getProvider',
            'findPartnerProduct',
            'getPartnerProduct',
            'getPartnerProductPurchPrice'
          ));
      }
    }

    public function update($id, $version, Request $request){
      $message = [
        'partner_product_id.required' => 'This field is required.',
        'gross_purch_price.required' => 'This field is required.',
        'gross_purch_price.numeric' => 'Numeric Only.',
        'datetime_start.required' => 'This field is required.',
        'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
        'datetime_end.required' => 'This field is required.',
        'tax_percentage.required_if'  => 'Tax Percentage required',
      ];

      $validator = Validator::make($request->all(), [
        'partner_product_id' => 'required',
        'gross_purch_price' => 'required|numeric',
        'datetime_start' => 'required|before_or_equal:datetime_end',
        'datetime_end' => 'required',
        'tax_percentage'      => 'required_if:flg_tax,1',
      ], $message);

      if($validator->fails()){
        return redirect()
          ->route('partner-product-purch-price.edit', ['id' => $id, 'version' => $version])
          ->withErrors($validator)
          ->withInput();
      }

      $findData = PartnerProductPurchPrice::find($request->partner_product_purch_price_id);

      if($findData == null){
        $retrun = 'index';
        $info = 'Data Partner Produk Price gagal diubah! Tidak dapat menemukan Partner Produk Price!';
        $alret = 'alert-danger';
      }
      else if($findData->version != $request->version){
        $retrun = 'index';
        $User = User::find($findData->update_user_id);
        $info = 'Data Partner Produk Price gagal diubah! Data Partner Produk Price telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else if(date('YmdHis', strtotime($request->datetime_end)) >= Carbon::now()->format('YmdHis')){

        $checkData = PartnerProductPurchPrice::where('partner_product_id',$findData->partner_product_id)
          ->whereNotIn(
              'partner_product_purch_price_id', [
                $findData->partner_product_purch_price_id
              ]
            )
          ->where('active',1)
          ->get();

        if(count($checkData) != 0){
          foreach ($checkData as $list){
            if(
              date('YmdHis', strtotime($request->datetime_start)) >= date('YmdHis', strtotime($list->datetime_start)) && 
              date('YmdHis', strtotime($request->datetime_start)) <= date('YmdHis', strtotime($list->datetime_end))
            ){ 
              $retrun= 'update';
              $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
              $alret = 'alert-danger';
              break;
            }
            else if(
              date('YmdHis', strtotime($request->datetime_start)) <= date('YmdHis', strtotime($list->datetime_start)) && 
              date('YmdHis', strtotime($request->datetime_end))  >= date('YmdHis', strtotime($list->datetime_end))
            ){
              $retrun= 'update';
              $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
              $alret = 'alert-danger';
              break;
            }
            else if(
              date('YmdHis', strtotime($request->datetime_end)) >= date('YmdHis', strtotime($list->datetime_start)) && 
              date('YmdHis', strtotime($request->datetime_end)) <= date('YmdHis', strtotime($list->datetime_end))
            ){
              $retrun= 'update';
              $info  = 'Status Partner Produk Price gagal diubah! Bersinggungan dengan periode yang aktif!';
              $alret = 'alert-danger';
              break;
            }
          }
        }
      }

      if(!isset($alret)){
        $retrun= 'index';
        $info  = 'Data Partner Produk Price Berhasil di update!';
        $alret = 'alert-success';
        DB::transaction(function() use($findData, $request){
          if(date('YmdHis', strtotime($request->datetime_end)) >= Carbon::now()->format('YmdHis')){
            $findData->active          = 1;
            $findData->active_datetime = Carbon::now()->format('YmdHis');
          }
          else if(date('YmdHis', strtotime($request->datetime_end)) < Carbon::now()->format('YmdHis')){
            $findData->active              = 0;
            $findData->non_active_datetime = Carbon::now()->format('YmdHis');
          }

          $findData->increment('version');
          $findData->partner_product_id = $request->partner_product_id;
          $findData->gross_purch_price  = $request->gross_purch_price;
          $findData->datetime_start     = date('YmdHis',strtotime($request->datetime_start));
          $findData->datetime_end       = date('YmdHis',strtotime($request->datetime_end));
          $findData->flg_tax            = $request->flg_tax == null ? 0 : 1;
          $findData->tax_percentage     = $request->flg_tax == null ? 0 : $request->tax_percentage;
          $findData->update_user_id     = Auth::id();
          $findData->update_datetime    = Carbon::now()->format('YmdHis');
          $findData->update();
        });
      }

      if($retrun == 'update'){
        return redirect()->route('partner-product-purch-price.edit', ['id' => $request->partner_product_purch_price_id, 'version' => $request->version])
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }
      else if($retrun == 'index'){
        return redirect()->route('partner-product-purch-price.index')
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }
    }

    public function ajaxGetProductPartner($partner, $provider){
      $getPartnerProduct = PartnerProduct::select(
          'partner_product_id',
          'partner_product_code',
          'partner_product_name'
        )
        ->where('partner_pulsa_id', $partner)
        ->where('provider_id', $provider)
        ->get();

      return $getPartnerProduct;
    }

    public function upload(){

      return view('partner-product-purchase-price.masal');
    }
}
