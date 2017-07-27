<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Provider;

use DB;
use Auth;
use Validator;

class ProviderController extends Controller{

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
		$getProvider = Provider::orderBy('provider_name', 'asc')
            ->get();

		return view('provider.index', compact(
			'getProvider'
		));
    }
    public function delete($id){
		$delete = Provider::find($id);
		$delete->delete();
		return redirect()->route('provider.index')
			->with('berhasil', 'Berhasil menghapus Provider ');
    }
    public function store(Request $request){
		$message = [
			'provider_name.required' => 'mohon isi',
			'provider_name.max' => 'Terlalu Panjang, Maks 25 Karakter',
			'provider_name.unique' => 'Provider ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'provider_name' => 'required|unique:amd_providers|max:25',
		], $message);

		if($validator->fails()){
			return redirect()->route('provider.index')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		$tahun = date('y');
        $bulan = date('m');
        $rand = rand(10,9999);

        $provider_code = 'SPT-'.$tahun.'-'.$bulan.'-'.$rand;

        $cek_kode = Provider::where('provider_code', $provider_code)->first();

        if(!$cek_kode){
          $provider_code;
        }else{
          $provider_code = 'Member Code is Empty - Contact Amadeo Please';
        }

		DB::transaction(function () use($request, $provider_code) {
			$save = new Provider;
			$save->provider_code	= $provider_code;
			$save->provider_name	= $request->provider_name;
			$save->version 			= '1';
			$save->create_user_id	= '1'/*Auth::user()->id*/;
			$save->update_user_id	= '1'/*Auth::user()->id*/;
			$save->save();
		});

		return redirect()->route('provider.index')
			->with('berhasil', 'Berhasil Menambahkan Provider '.$request->provider_name);
    }
    public function update(Request $request){
		$message = [
			'provider_name.required' => 'mohon isi',
			'provider_name.max' => 'Terlalu Panjang, Maks 25 Karakter',
			'provider_name.unique' => 'Provider ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'provider_name' => 'required|unique:amd_providers|max:25',
		], $message);

		if($validator->fails()){
			return redirect()->route('provider.index')
				->withErrors($validator)->withInput()->with('update-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$update = Provider::find($request->id_provider);
			$update->provider_name	= $request->provider_name;
			$update->version 		= '1';
			$update->update_user_id	= '1'/*Auth::user()->id*/;
			$update->update();
		});

		return redirect()->route('provider.index')
			->with('berhasil', 'Berhasil Memperbarui Provider '.$request->provider_name);
    }
}
