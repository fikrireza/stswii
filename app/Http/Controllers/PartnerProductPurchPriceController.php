<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PartnerProductPurchPrice;
use App\Models\PartnerProduct;

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
      $past     = true;

      if($status == 1){ // cek jika ada data produk aktif yang bentrok
        // Start Check if exist - jo
        $checkData = PartnerProductPurchPrice::where('partner_product_id',$findData->partner_product_id)
          ->where('active',1)
          ->get();

        foreach ($checkData as $list)
        {
          if($list->datetime_start <= date('Ymd', strtotime($findData->datetime_start)) && 
            date('Ymd', strtotime($findData->datetime_start)) <= $list->datetime_end)
          { 
            return redirect()->route('partner-product-purch-price.index')
            ->with('alret', 'alert-danger')
            ->with('berhasil', 'Data dengan periode yang sama aktif.');
          }
          if($list->datetime_start <= date('Ymd', strtotime($findData->datetime_end)) && date('Ymd', strtotime($findData->datetime_end)) <= $list->datetime_end)
          {
            return redirect()->route('partner-product-purch-price.index')
            ->with('alret', 'alert-danger')
            ->with('berhasil', 'Data dengan periode yang sama aktif.');
          }
        }
        // End Check if exist - jo
      }      
      
      if($findData == null){
        $info = 'Status Partner Produk Price gagal diubah! Tidak dapat menemukan Partner Produk Price!';
        $alret = 'alert-danger';
      }
      else if($findData->version != $version){
        $User = User::find($findData->update_user_id);
        $info = 'Status Partner Produk Price gagal diubah! Data Partner Produk Price telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil Memperbarui Status Partner Produk '.$findData->partner_product_name;
        $alret = 'alert-success';
        DB::transaction(function () use($findData, $status) {
          $findData->increment('version');
          $findData->active         = $status;
          $findData->update_user_id = Auth::user()->id;
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

    public function delete($id){
        $findData = PartnerProductPurchPrice::find($id);

        if(!$findData){
          return view('errors.404');
        }

        $findData->delete();

        return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Successfully Deleted '.$findData->gross_purch_price);
    }

    public function tambah(){

        $getPartnerProduct = PartnerProduct::select(
            'partner_pulsa_id',
            'partner_product_id',
            'partner_product_code',
            'partner_product_name'
          )
          ->get();

        return view('partner-product-purchase-price.tambah',compact(
            'getPartnerProduct'
          ));
    }

    public function store(Request $request){
      // dd($request->flg_tax);
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

      // Start Check if exist - pikri

        // $cekSellPrice = PartnerProductPurchPrice::where(
        // 			'partner_product_id', $request->partner_product_id
        // 		)
        //       ->whereDate('datetime_start', '>=', $request->datetime_start)
        //       ->whereDate('datetime_end', '<=', $request->datetime_end)
        //       ->where('active', 1)
        //       ->first();

        // if($cekSellPrice){
        //   return redirect()->route('partner-product-purch-price.tambah')
        //   	->withInput()
        //   ->with('alret', 'alert-danger')
        //   	->with(
        //   		'berhasil',
        //   		'This period has been set for '.$cekSellPrice->partnerpulsa->partner_product_name.' with Gross Purches Price Rp. '.number_format($cekSellPrice->gross_purch_price, 0,',','.')
        //   	);
        // }
      // End Check if exist

      // Start Check if exist - jo
      $checkData = PartnerProductPurchPrice::where('partner_product_id',$request->partner_product_id)
        ->where('active',1)
        ->get();

      foreach ($checkData as $list)
      {
        if($list->datetime_start <= date('Ymd', strtotime($request->datetime_start)) && 
          date('Ymd', strtotime($request->datetime_start)) <= $list->datetime_end/* && 
          isset($request->active)*/)
        { 
          return redirect()->route('partner-product-purch-price.tambah')
          ->with('alret', 'alert-danger')
          ->with('berhasil', 'Data is still active.')->withInput();
        }
        if($list->datetime_start <= date('Ymd', strtotime($request->datetime_end)) && date('Ymd', strtotime($request->datetime_end)) <= $list->datetime_end/* && isset($request->active)*/)
        {
          return redirect()->route('partner-product-purch-price.tambah')
          ->with('alret', 'alert-danger')
          ->with('berhasil', 'Data is still active.')->withInput();
        }
      }
      // End Check if exist - jo

      DB::transaction(function() use($request){
        $save = New PartnerProductPurchPrice;
        $save->partner_product_id = $request->partner_product_id;
        $save->gross_purch_price 	= $request->gross_purch_price;
        $save->datetime_start 		= date('Ymd',strtotime($request->datetime_start));
        $save->datetime_end 		  = date('Ymd',strtotime($request->datetime_end));
        $save->flg_tax            = $request->flg_tax == null ? 0 : 1;
        $save->tax_percentage     = $request->flg_tax == null ? 0 : $request->tax_percentage;
        $save->active             = /*isset($request->active) ? */1 /*: 0*/;
        $save->active_datetime    = Carbon::now()->format('YmdHis');
        $save->non_active_datetime= '';
        $save->version            = 0;
        $save->create_user_id     = Auth::user()->id;
        $save->create_datetime    = Carbon::now()->format('YmdHis');
        $save->update_user_id     = 0;/*Auth::user()->id*/
        $save->update_datetime    = '';
        $save->save();
      });

      return redirect()->route('partner-product-purch-price.tambah')
        ->with('alret', 'alert-success')
        ->with('berhasil', 'Your data has been successfully saved.');
    }

    public function edit($id){

        return view('partner-product-purchase-price.ubah', compact('getPartnerProductPurchPrice','getPartnerProduct'));
    }

    public function update(Request $request){
      $message = [
			'partner_product_id.required' => 'This field is required.',
			'gross_purch_price.required' => 'This field is required.',
			'gross_purch_price.numeric' => 'Numeric Only.',
			'datetime_start.required' => 'This field is required.',
			'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
			'datetime_end.required' => 'This field is required.',
        ];
        $validator = Validator::make($request->all(), [
			'partner_product_id' => 'required',
			'gross_purch_price' => 'required|numeric',
			'datetime_start' => 'required|before_or_equal:datetime_end',
			'datetime_end' => 'required',
        ], $message);

        if($validator->fails()){
			return redirect()->route('partner-product-purch-price.edit', ['id' => $request->product_purch_price_id])->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
          $update = PartnerProductPurchPrice::find($request->product_purch_price_id);
          $update->partner_product_id 	= $request->partner_product_id;
          $update->gross_purch_price	= $request->gross_purch_price;
          $update->datetime_start 		= $request->datetime_start;
          $update->datetime_end 		= $request->datetime_end;
          if($request->flg_tax == 1){
            $update->flg_tax = 1;
            $update->tax_percentage = $request->tax_percentage;
          }
          if($request->active == 1){
            $update->active = 1;
            $update->active_datetime = date('Y-m-d H:i:s');
          }else{
            $update->active = 0;
            $update->non_active_datetime = date('Y-m-d H:i:s');
          }
          $update->version = 1;
          $update->create_user_id = 1; //Auth::user()->id;
          $update->update();
        });

        return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Your data has been successfully updated.');
    }

    public function upload(){

      return view('partner-product-purchase-price.masal');
    }
}
