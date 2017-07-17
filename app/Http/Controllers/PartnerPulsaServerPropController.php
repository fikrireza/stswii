<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PartnerPulsaServerProp;

use DB;
use Auth;
use Validator;

class PartnerPulsaServerPropController extends Controller{
	public function index(){
		$getPartnerServer = PartnerPulsaServerProp::get();
		return view('partner-server.index', compact(
			'getPartnerServer'
		));
    }
    public function delete($id){
		$delete = PartnerPulsaServerProp::find($id);
		$delete->delete();
		return redirect()->route('partner-server.index')
			->with('berhasil', 'Berhasil menghapus Prartner Server ');
    }
    public function store(Request $request){
		$message = [
			'server_url.required' => 'mohon isi',
			'server_url.unique' => 'Server Url ini sudah ada',
			'api_key.required' => 'mohon isi',
			'api_key.unique' => 'Api Key ini sudah ada',
			'api_secret.required' => 'mohon isi',
			'api_secret.unique' => 'Api Secret ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'server_url' 	=> 'required|unique:amd_partner_pulsa_server_props',
			'api_key' 		=> 'required|unique:amd_partner_pulsa_server_props',
			'api_secret' 	=> 'required|unique:amd_partner_pulsa_server_props',
		], $message);

		if($validator->fails()){
			return redirect()->route('partner-server.index')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$save = new PartnerPulsaServerProp;
			$save->server_url		= $request->server_url;
			$save->api_key			= $request->api_key;
			$save->api_secret		= $request->api_secret;
			$save->version 			= '1';
			$save->create_user_id	= '1'/*Auth::user()->id*/;
			$save->update_user_id	= '1'/*Auth::user()->id*/;
			$save->save();
		});

		return redirect()->route('partner-server.index')
			->with('berhasil', 'Berhasil Menambahkan Prartner Server '.$request->server_url);
    }
    public function update(Request $request){
		$message = [
			'server_url.required' => 'mohon isi',
			'server_url.unique' => 'Server Url ini sudah ada',
			'api_key.required' => 'mohon isi',
			'api_key.unique' => 'Api Key ini sudah ada',
			'api_secret.required' => 'mohon isi',
			'api_secret.unique' => 'Api Secret ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'server_url' 	=> 'required|unique:amd_partner_pulsa_server_props',
			'api_key' 		=> 'required|unique:amd_partner_pulsa_server_props',
			'api_secret' 	=> 'required|unique:amd_partner_pulsa_server_props',
		], $message);

		if($validator->fails()){
			return redirect()->route('partner-server.index')
				->withErrors($validator)->withInput()->with('update-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$update = PartnerPulsaServerProp::find($request->id_data);
			$update->server_url		= $request->server_url;
			$update->api_key		= $request->api_key;
			$update->api_secret		= $request->api_secret;
			$update->version 		= '1';
			$update->update_user_id	= '1'/*Auth::user()->id*/;
			$update->update();
		});

		return redirect()->route('partner-server.index')
			->with('berhasil', 'Berhasil Memperbarui Prartner Server '.$request->server_url);
    }
}
