<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PartnerPulsa;

class PartnerPulsaController extends Controller
{
    public function index()
    {
    	$getPartnerPulsa = PartnerPulsa::all();
    	return view('partner-pulsa.index', compact(
			'getPartnerPulsa'
		));
    }

    public function store(Request $request)
    {
    	$message = [
			'partner_pulsa_code.required' => 'mohon isi',
			'partner_pulsa_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
			'partner_pulsa_code.unique' => 'Produk ini sudah ada',
			'partner_pulsa_name.required' => 'mohon isi',
		];

		$validator = Validator::make($request->all(), [
			'partner_pulsa_code' => 'required|unique:amd_providers',
			'partner_pulsa_name' => 'required',
		], $message);

		if($validator->fails()){
			return redirect()->route('PartnerPulsaController.index')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$save = new PartnerPulsa;

			$save->partner_pulsa_code  = $request->partner_pulsa_code;
			$save->description         = $request->description;
			$save->partner_pulsa_name  = $request->partner_pulsa_name;

			$save->flg_need_deposit    = isset($request->flg_need_deposit) ? 1 : 0;;
			$save->payment_termin      = '1'/* $save->payment_termin */;

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

		return redirect()->route('PartnerPulsaController.index')
			->with('berhasil', 'Berhasil Menambahkan Provider '.$request->partner_pulsa_name);
    }

    public function edit($id)
    {
    	$index = PartnerPulsa::find($id);

    	return view('partner-pulsa.edit')->with(compact(''));
    }

    public function update($id, Request $request)
    {
    	$message = [
			'partner_pulsa_code.required' => 'mohon isi',
			'partner_pulsa_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
			'partner_pulsa_code.unique' => 'Produk ini sudah ada',
			'partner_pulsa_name.required' => 'mohon isi',
		];

		$validator = Validator::make($request->all(), [
			'partner_pulsa_code' => 'required|unique:amd_providers',
			'partner_pulsa_name' => 'required',
		], $message);

		if($validator->fails()){
			return redirect()->route('PartnerPulsaController.index')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		DB::transaction(function () use($request, $id) {
			$save = PartnerPulsa::find($id);

			$save->partner_pulsa_code  = $request->partner_pulsa_code;
			$save->description         = $request->description;
			$save->partner_pulsa_name  = $request->partner_pulsa_name;

			$save->flg_need_deposit    = isset($request->flg_need_deposit) ? 1 : 0;;
			$save->payment_termin      = '1'/* $save->payment_termin */;

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

		return redirect()->route('PartnerPulsaController.index')
			->with('berhasil', 'Berhasil Mengubah Provider '.$request->partner_pulsa_name);
    }

    public function active($id)
    {
    	DB::transaction(function () use($request, $id) {
			$save = PartnerPulsa::find($id);

			$save->partner_pulsa_code  = $request->partner_pulsa_code;
			$save->description         = $request->description;
			$save->partner_pulsa_name  = $request->partner_pulsa_name;

			$save->flg_need_deposit    = isset($request->flg_need_deposit) ? 1 : 0;;
			$save->payment_termin      = '1'/* $save->payment_termin */;

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

		return redirect()->route('PartnerPulsaController.index')
			->with('berhasil', 'Berhasil Mengubah Provider '.$request->partner_pulsa_name);
    }

    public function delete($id)
    {
    	$delete = Provider::find($id);
		$delete->delete();
		return redirect()->route('PartnerPulsaController.index')
			->with('berhasil', 'Berhasil menghapus Partner Pulsa ');
    }
}
