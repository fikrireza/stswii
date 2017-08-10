<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

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


    public function index(Request $request){
      $provider = Provider::get();
      $partner = PartnerPulsa::get();

      $message = [
        'f_provider.integer' => 'Invalid filter',
        'f_partner.integer' => 'Invalid filter',
        'f_active.integer' => 'Invalid filter',
        'f_active.in' => 'Invalid filter',
      ];

      $validator = Validator::make($request->all(), [
        'f_provider' => 'integer|nullable',
        'f_partner' => 'integer|nullable',
        'f_active' => 'integer|nullable|in:0,1',
      ], $message);

      if($validator->fails())
      {
        return redirect()->route('product-sell-price.index');
      }

      // $getPPPP = PartnerProductPurchPrice::select(
      //     'partner_product_id',
      //     'partner_product_purch_price_id',
      //     'gross_purch_price',
      //     'flg_tax',
      //     'tax_percentage',
      //     'datetime_start',
      //     'datetime_end',
      //     'active',
      //     'version'
      //   )
      //   ->get();

      return view('partner-product-purchase-price.index', compact(
          // 'getPPPP'
        'request', 
        'provider',
        'partner'
      ));
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

    public function yajraGetData(Request $request){

      $f_provider = $request->query('f_provider');
      $f_partner = $request->query('f_partner');
      $f_active = $request->query('f_active');

      $getDatas = PartnerProductPurchPrice::leftJoin(
          'sw_partner_product', 
          'sw_partner_product.partner_product_id',
          'sw_partner_product_purch_price.partner_product_id'
        )
        ->select([
          'sw_partner_product.partner_product_code as partner_product_code',
          'sw_partner_product.partner_product_name as partner_product_name',
          'partner_product_purch_price_id',
          'gross_purch_price',
          'flg_tax',
          'tax_percentage',
          'datetime_start',
          'datetime_end',
          'sw_partner_product_purch_price.active as active',
          'sw_partner_product_purch_price.version as version'
        ]);

      if($f_provider != null){
        $getDatas->where('sw_partner_product.provider_id', $f_provider);
      }
      if($f_partner != null){
        $getDatas->where('sw_partner_product.partner_pulsa_id', $f_partner);
      }
      if($f_active != null){
        $getDatas->where('sw_partner_product_purch_price.active', $f_active);
      }
      $getDatas = $getDatas->get();
      
      $start=1;
      $Datatables = Datatables::of($getDatas)
        ->addColumn('slno', function ($getData) use (&$start) {
            return $start++;
        })
        ->editColumn('gross_purch_price',  function ($getData){
                return 'Rp. '.number_format($getData->gross_purch_price, 2);
            })
        ->editColumn('flg_tax',  function ($getData){
          if($getData->flg_tax == 1){
            return "Y";
          }
          else if($getData->flg_tax == 0){
            return "N";
          }
        })
        ->editColumn('tax_percentage',  function ($getData){
          if($getData->flg_tax == 1){
            return $getData->tax_percentage."%";
          }
          else if($getData->flg_tax == 0){
            return "0%";
          }
        })
        ->editColumn('datetime_start',  function ($getData){
            return date('d-m-Y H:i:s', strtotime($getData->datetime_start));
        })
        ->editColumn('datetime_end',  function ($getData){
            return date('d-m-Y H:i:s', strtotime($getData->datetime_end));
        })
        ->addColumn('action', function ($getData) {
          $actionHtml = '';
          if (Auth::user()->can('update-partner-product-purch-price')) {
            $actionHtml = $actionHtml." 
              <a 
                href='".route('partner-product-purch-price.edit', ['id' => $getData->partner_product_purch_price_id, 'version' => $getData->version ])."''
                class='btn btn-xs btn-warning btn-sm' 
                data-toggle='tooltip'
                data-placement='top' 
                title='Ubah'
              ><i class='fa fa-pencil'></i></a>";
          }
          if (Auth::user()->can('delete-partner-product-purch-price')) {
            $actionHtml = $actionHtml." 
              <a 
                href='' 
                class='delete' 
                data-value='".$getData->partner_product_purch_price_id."' 
                data-version='".$getData->version."' 
                data-toggle='modal' 
                data-target='.modal-delete'
              >
                <span 
                  class='btn btn-xs btn-danger btn-sm' 
                  data-toggle='tooltip' 
                  data-placement='top' 
                  title='Hapus'
                ><i class='fa fa-remove'></i></span>
              </a>";
          }
            return $actionHtml;
        });

      if (Auth::user()->can('activate-partner-product-purch-price')) {
        $Datatables = $Datatables->editColumn('active', function ($getData){
          if($getData->active == 1){
            return "
              <a 
                href='' 
                class='unpublish' 
                data-value='".$getData->partner_product_purch_price_id."' 
                data-version='".$getData->version."' 
                data-toggle='modal' 
                data-target='.modal-nonactive'
              >
                <span 
                  class='label label-success' 
                  data-toggle='tooltip' 
                  data-placement='top' 
                  title='Active'
                >Active</span>
              </a><br>";
          }
          else if($getData->active == 0){
            return "
              <a 
                href='' 
                class='publish' 
                data-value='".$getData->partner_product_purch_price_id."' 
                data-version='".$getData->version."' 
                data-toggle='modal' 
                data-target='.modal-active'
              >
                <span 
                  class='label label-danger' 
                  data-toggle='tooltip' 
                  data-placement='top' 
                  title='Non Active'
                >Non Active</span>
              </a><br>";
          }
        });
      }

      $Datatables = $Datatables
          ->escapeColumns(['*'])
          ->make(true);

      return $Datatables;
    }

    public function upload(){

      return view('partner-product-purchase-price.masal');
    }
}
