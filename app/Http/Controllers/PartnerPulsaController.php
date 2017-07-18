<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PartnerPulsa;

use Auth;
use DB;
use Validator;

class PartnerPulsaController extends Controller
{
    public function index()
    {
    	$getPartnerPulsa = PartnerPulsa::all();
    	return view('partner-pulsa.index', compact(
			'getPartnerPulsa'
		));
    }

    public function create()
    {
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

    	return view('partner-pulsa.create', compact(
			'partner_pulsa_code'
		));
    }

    public function store(Request $request)
    {
    	$message = [
			'partner_pulsa_code.required' => 'mohon isi',
			'partner_pulsa_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
			'partner_pulsa_code.unique' => 'Produk ini sudah ada',
			'partner_pulsa_name.required' => 'mohon isi',
			'description.required' => 'mohon isi',
		];

		$validator = Validator::make($request->all(), [
			'partner_pulsa_code' => 'required|unique:amd_partner_pulsas',
			'partner_pulsa_name' => 'required',
			'description' => 'required',
		], $message);

		if($validator->fails()){
			return redirect()->route('partner-pulsa.create')
				->withErrors($validator);
		}

		DB::transaction(function () use($request) {
			$save = new PartnerPulsa;

			$save->partner_pulsa_code  = $request->partner_pulsa_code;
			$save->description         = $request->description;
			$save->partner_pulsa_name  = $request->partner_pulsa_name;

			$save->flg_need_deposit    = isset($request->flg_need_deposit) ? 1 : 0;;
			$save->payment_termin      = isset($request->flg_need_deposit) ? $request->payment_termin : 0;

			$save->active              = isset($request->active) ? 1 : 0;
			$save->active_datetime     = date('Y-m-d H:i:s');
			$save->non_active_datetime = date('Y-m-d H:i:s');

			$save->version 		       = '1';
			$save->create_user_id	   = '1'/*Auth::user()->id*/;
			$save->update_user_id	   = '1'/*Auth::user()->id*/;
			$save->save();
		});

		return redirect()->route('partner-pulsa.index')
			->with('berhasil', 'Berhasil Menambahkan Provider '.$request->partner_pulsa_name);
    }

    public function edit($id)
    {
    	$getPartnerPulsa = PartnerPulsa::find($id);

    	if(!$getPartnerPulsa){
          return view('errors.404');
        }

    	return view('partner-pulsa.edit')->with(compact('getPartnerPulsa'));
    }

    public function update($id, Request $request)
    {
    	$message = [
			'partner_pulsa_code.required' => 'mohon isi',
			'partner_pulsa_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
			'partner_pulsa_code.unique' => 'Produk ini sudah ada',
			'partner_pulsa_name.required' => 'mohon isi',
			'description.required' => 'mohon isi',
		];

		$validator = Validator::make($request->all(), [
			'partner_pulsa_code' => 'required|unique:amd_partner_pulsas,partner_pulsa_code,'.$id,
			'partner_pulsa_name' => 'required',
			'description' => 'required',
		], $message);

		if($validator->fails()){
			return redirect()->route('partner-pulsa.edit', ['id' => $id])
				->withErrors($validator)->withInput();
		}

		DB::transaction(function () use($request, $id) {
			$save = PartnerPulsa::find($id);

			$save->partner_pulsa_code  = $request->partner_pulsa_code;
			$save->description         = $request->description;
			$save->partner_pulsa_name  = $request->partner_pulsa_name;

			$save->flg_need_deposit    = isset($request->flg_need_deposit) ? 1 : 0;;
			$save->payment_termin      = isset($request->flg_need_deposit) ? $request->payment_termin : 0;

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

		return redirect()->route('partner-pulsa.index')
			->with('berhasil', 'Berhasil Mengubah Provider '.$request->partner_pulsa_name);
    }

    public function active($id)
    {
        $getPartnerPulsa = PartnerPulsa::find($id);

        if(!$getPartnerPulsa){
          return view('errors.404');
        }

        if ($getPartnerPulsa->active == 1) {
          $getPartnerPulsa->active = 0;
          $getPartnerPulsa->non_active_datetime = date('Y-m-d H:i:s');
          $getPartnerPulsa->update_user_id = 1; //Auth::user()->id;
          $getPartnerPulsa->update();

          return redirect()->route('partner-pulsa.index')->with('berhasil', 'Successfully Nonactive'.$getPartnerPulsa->partner_pulsa_name);
        }else{
          $getPartnerPulsa->active = 1;
          $getPartnerPulsa->active_datetime = date('Y-m-d H:i:s');
          $getPartnerPulsa->update_user_id = 1; //Auth::user()->id;
          $getPartnerPulsa->update();

          return redirect()->route('partner-pulsa.index')->with('berhasil', 'Successfully Activated '.$getPartnerPulsa->partner_pulsa_name);
        }
    }

    public function delete($id)
    {
    	$getPartnerPulsa = PartnerPulsa::find($id);

    	if(!$getPartnerPulsa){
          return view('errors.404');
        }

		$getPartnerPulsa->delete();
		return redirect()->route('partner-pulsa.index')
			->with('berhasil', 'Berhasil menghapus Partner Pulsa ');
    }

}
