<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PartnerPulsaServerProp;
use App\Models\PartnerPulsa;

use DB;
use Auth;
use Validator;

class PartnerPulsaServerPropController extends Controller{

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
		$index = PartnerPulsaServerProp::all();

		return view('partner-server.index', compact('index'));
    }


    public function delete($id)
	{
		$delete = PartnerPulsaServerProp::find($id);
		$delete->delete();

		return redirect()->route('partner-server.index')->with('berhasil', 'Berhasil menghapus Prartner Server ');
    }

    public function store(Request $request)
    {
		$message = [
			'server_url.required' => 'mohon isi',
			'server_url.unique' => 'Server Url ini sudah ada',
			'api_key.required' => 'mohon isi',
			'api_key.unique' => 'Api Key ini sudah ada',
			'api_secret.required' => 'mohon isi',
			'api_secret.unique' => 'Api Secret ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'server_url' 	   => 'required|unique:sw_partner_pulsa_server_properties',
			'api_key' 		   => 'required|unique:sw_partner_pulsa_server_properties',
			'api_secret' 	   => 'required|unique:sw_partner_pulsa_server_properties',
		], $message);

		if($validator->fails()){
			return redirect()->route('partner-server.create')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$index = new PartnerPulsaServerProp;

			$index->server_url       = $request->server_url;
			$index->api_key          = $request->api_key;
			$index->api_secret       = $request->api_secret;

			$index->version          = 0;
			$index->create_datetime  = date('YmdHis');
			$index->create_user_id   = Auth::id();
			$index->update_datetime  = 00000000000000;
			$index->update_user_id   = 0;

			$index->save();
		});

		return redirect()->route('partner-server.index')
			->with('berhasil', 'Berhasil Menambahkan Prartner Server '.$request->server_url);
    }
    public function update(Request $request)
    {
		$message = [
			'server_url.required' => 'mohon isi',
			'server_url.unique' => 'Server Url ini sudah ada',
			'api_key.required' => 'mohon isi',
			'api_key.unique' => 'Api Key ini sudah ada',
			'api_secret.required' => 'mohon isi',
			'api_secret.unique' => 'Api Secret ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'server_url' 	   => 'required|unique:sw_partner_pulsa_server_properties,server_url,'.$request->id_data.',partner_pulsa_id',
			'api_key' 	   	   => 'required|unique:sw_partner_pulsa_server_properties,api_key,'.$request->id_data.',partner_pulsa_id',
			'api_secret' 	   => 'required|unique:sw_partner_pulsa_server_properties,api_secret,'.$request->id_data.',partner_pulsa_id',
		], $message);

		if($validator->fails()){
			return redirect()->route('partner-server.index')
				->withErrors($validator)->withInput()->with('update-false', 'Something Errors');
		}

		$index = PartnerPulsaServerProp::find($request->id_data);

		if($index->version != $request->version)
		{
			return redirect()->route('partner-server.index')
				->withErrors($validator)->withInput()->with('update-false', 'Your data already updated by ' . $index->updatedBy->name . '.');
		}

		$index->server_url       = $request->server_url;
		$index->api_key          = $request->api_key;
		$index->api_secret       = $request->api_secret;

		$index->version          += 1;
		$index->update_datetime  = date('YmdHis');
		$index->update_user_id   = Auth::id();

		$index->update();

		return redirect()->route('partner-server.index')
			->with('berhasil', 'Berhasil Memperbarui Prartner Server '.$request->server_url);
    }
}
