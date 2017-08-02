<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Provider;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;

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
		$getProvider = Provider::select(
				'provider_id',
				'provider_code',
				'provider_name',
				'version',
				DB::raw('(select count(provider_prefix_id) from sw_provider_prefix where sw_provider.provider_id = sw_provider_prefix.provider_id) as count_provider_prefix')
			)
			->orderBy('provider_name', 'asc')
            ->get();

        $newProvCode = 'prov-'.rand(1,99);

        $cekCode = Provider::where('provider_code', $newProvCode)->first();
        if(!$cekCode){
          return view('provider.index', compact(
				'getProvider',
				'newProvCode'
			));
        }
        // else{
        //    dd('Provider Code is Empty - Contact Amadeo Please');
        // }
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
			'provider_code.unique' => 'Provider Code ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'provider_code' => 'unique:sw_provider',
			'provider_name' => 'required|unique:sw_provider|max:25',
		], $message);

		if($validator->fails()){
			return redirect()->route('provider.index')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$save = new Provider;
			$save->provider_code	= $request->provider_code;
			$save->provider_name	= $request->provider_name;
			$save->version 			= 1;
			$save->create_user_id	= Auth::user()->id;
			$save->create_datetime	= Carbon::now()->format('YmdHis');
			$save->update_user_id	= -99;/*Auth::user()->id*/
			$save->update_datetime	= '';
			$save->save();
		});

		return redirect()->route('provider.index')
			->with('alret', 'alert-success')
			->with('berhasil', 'Berhasil Menambahkan Provider '.$request->provider_name);
    }
    public function update(Request $request){
		$message = [
			'provider_name.required' => 'mohon isi',
			'provider_name.max' => 'Terlalu Panjang, Maks 25 Karakter',
			'provider_name.unique' => 'Provider ini sudah ada',
		];

		$validator = Validator::make($request->all(), [
			'provider_name' => 'required|unique:sw_provider|max:25',
		], $message);

		if($validator->fails()){
			return redirect()->route('provider.index')
				->withErrors($validator)->withInput()->with('update-false', 'Something Errors');;
		}

		$update = Provider::find($request->provider_id);

		if($update->version != $request->version){
			$User = User::find($update->update_user_id);
			$info = 'Provider gagal diupdate! Provider telah diupdate Oleh '.$User->name;
			$alret = 'alert-danger';
		}
		else{
			$info = 'Berhasil Memperbarui Provider '.$request->provider_name;
			$alret = 'alert-success';
			DB::transaction(function () use($request, $update) {
				$update->increment('version');
				$update->provider_name	= $request->provider_name;
				$update->update_user_id	= Auth::user()->id;
				$update->update_datetime= Carbon::now()->format('YmdHis');
				$update->update();
			});
		}

		return redirect()->route('provider.index')
			->with('berhasil', $info)
			->with('alret', $alret);
    }
}
