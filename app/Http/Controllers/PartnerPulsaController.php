<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PartnerPulsa;

use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class PartnerPulsaController extends Controller
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
      $getPartner = PartnerPulsa::select(
          'partner_pulsa_id',
          'partner_pulsa_code',
          'partner_pulsa_name',
          'description',
          'type_top',
          'payment_termin',
          'active',
          'version'
        )
        ->get();

    	return view('partner-pulsa.index', compact('getPartner'));
    }

    public function create(){
        $tahun = date('y');
        $bulan = date('m');
        $rand = rand(1000,9999);

        $partner_pulsa_code = 'SPT-'.$tahun.'-'.$bulan.'-'.$rand;

        $cek_kode = PartnerPulsa::where('partner_pulsa_code', $partner_pulsa_code)->first();

        if(!$cek_kode){
          $partner_pulsa_code;
        }else{
          $partner_pulsa_code = 'Partner Pulsa Code is Empty - Please Contact Amadeo';
        }

      	return view('partner-pulsa.tambah', compact('partner_pulsa_code'));
    }

    public function store(Request $request){
      $message = [
  			'partner_pulsa_code.required' => 'mohon isi',
  			'partner_pulsa_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
  			'partner_pulsa_code.unique' => 'Produk ini sudah ada',
  			'partner_pulsa_name.required' => 'mohon isi',
        'description.required' => 'mohon isi',
        'type_top.required' => 'mohon isi',
  			// 'payment_termin.required' => 'mohon isi',
  		];

  		$validator = Validator::make($request->all(), [
  			'partner_pulsa_code' => 'required|unique:sw_partner_pulsa',
  			'partner_pulsa_name' => 'required',
        'description' => 'required',
        'type_top' => 'required',
  			// 'payment_termin' => 'required',
  		], $message);

  		if($validator->fails()){
  			return redirect()->route('partner-pulsa.create')
  				->withErrors($validator)->withInput();
  		}

  		DB::transaction(function () use($request) {
  			$save = new PartnerPulsa;

  			$save->partner_pulsa_code  = $request->partner_pulsa_code;
  			$save->partner_pulsa_name  = $request->partner_pulsa_name;
        $save->description         = $request->description;
        $save->type_top            = $request->type_top;
  			$save->payment_termin      = $request->type_top == 'TERMIN' ? 1 : 0;
  			$save->active              = /*isset($request->active) ? */1/* : 0*/;
  			$save->active_datetime     = Carbon::now()->format('YmdHis');
  			$save->non_active_datetime = '';
  			$save->version 		         = '1';
  			$save->create_user_id	     = Auth::user()->id;
        $save->create_datetime     = Carbon::now()->format('YmdHis');
        $save->update_user_id      = -99;/*Auth::user()->id*/
        $save->update_datetime     = '';
  			$save->save();
  		});

  		return redirect()->route('partner-pulsa.index')
        ->with('alret', 'alert-success')
        ->with('berhasil', 'Berhasil Menambahkan Partner '.$request->partner_pulsa_name);
    }

    public function active($id, $version, $status){
      $getPartnerPulsa = PartnerPulsa::find($id);

      if($getPartnerPulsa == null){
        $info = 'Status Partner gagal diubah! Tidak dapat menemukan Partner!';
        $alret = 'alert-danger';
      }
      else if($getPartnerPulsa->version != $version){
        $User = User::find($getPartnerPulsa->update_user_id);
        $info = 'Status Partner gagal diubah! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil Memperbarui Status Partner '.$getPartnerPulsa->partner_pulsa_name;
        $alret = 'alert-success';
        DB::transaction(function () use($getPartnerPulsa, $status) {
          $getPartnerPulsa->increment('version');
          $getPartnerPulsa->active         = $status;
          $getPartnerPulsa->update_user_id = Auth::user()->id;
          $getPartnerPulsa->update_datetime= Carbon::now()->format('YmdHis');
          $getPartnerPulsa->non_active_datetime= Carbon::now()->format('YmdHis');
          $getPartnerPulsa->update();
        });
      }
      return redirect()->route('partner-pulsa.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function edit($id, $version){
      $getPartnerPulsa = PartnerPulsa::find($id);

      if($getPartnerPulsa == null){
        $retrun = 'index';
        $info = 'Tidak dapat menemukan Partner!';
        $alret = 'alert-danger';
      }
      else if($getPartnerPulsa->version != $version){
        $User = User::find($getPartnerPulsa->update_user_id);
        $retrun = 'index';
        $info = 'Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $retrun = 'update';
      }

      if($retrun == 'index'){
        return redirect()->route('partner-pulsa.index')
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }
      else if($retrun == 'update'){
      	return view('partner-pulsa.ubah', compact('getPartnerPulsa'));
      }
    }

    public function update($id, $version, Request $request){
        $message = [
        'partner_pulsa_name.required' => 'mohon isi',
        'description.required' => 'mohon isi',
        'type_top.required' => 'mohon isi',
        // 'payment_termin.required' => 'mohon isi',
      ];

      $validator = Validator::make($request->all(), [
        'partner_pulsa_name' => 'required',
        'description' => 'required',
        'type_top' => 'required',
        // 'payment_termin' => 'required',
      ], $message);

    		if($validator->fails()){
    			return redirect()->route('partner-pulsa.edit', ['id' => $id, 'version' => $version])
    				->withErrors($validator)->withInput();
    		}

    		// DB::transaction(function () use($request, $id) {
    		// 	$save = PartnerPulsa::find($id);

    		// 	$save->partner_pulsa_code  = $request->partner_pulsa_code;
    		// 	$save->description         = $request->description;
    		// 	$save->partner_pulsa_name  = $request->partner_pulsa_name;

    		// 	$save->flg_need_deposit    = isset($request->flg_need_deposit) ? 1 : 0;;
    		// 	$save->payment_termin      = isset($request->flg_need_deposit) ? $request->payment_termin : 0;

    		// 	$save->active              = isset($request->active) ? 1 : 0;
    		// 	if(isset($request->active))
    		// 	{
    		// 		$save->active_datetime     = date('Y-m-d H:i:s');
    		// 	}
    		// 	else
    		// 	{
    		// 		$save->non_active_datetime = date('Y-m-d H:i:s');
    		// 	}

    		// 	$save->version 		       = '1';
    		// 	$save->create_user_id	   = '1'/*Auth::user()->id*/;
    		// 	$save->update_user_id	   = '1'/*Auth::user()->id*/;
    		// 	$save->save();
    		// });

    		// return redirect()->route('partner-pulsa.index')
    		// 	->with('berhasil', 'Berhasil Mengubah Provider '.$request->partner_pulsa_name);
    }

    public function delete($id){
    	$getPartnerPulsa = PartnerPulsa::find($id);

    	if(!$getPartnerPulsa){
          return view('errors.404');
        }

  		$getPartnerPulsa->delete();
  		return redirect()->route('partner-pulsa.index')
  			->with('berhasil', 'Berhasil menghapus Partner Pulsa ');
    }

}
