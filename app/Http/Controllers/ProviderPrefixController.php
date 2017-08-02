<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Provider;
use App\Models\ProviderPrefix;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;

class ProviderPrefixController extends Controller{


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
				'provider_name'
			)
		    ->get();

		$getProviderPrefix = ProviderPrefix::select(
				'provider_id',
				'provider_prefix_id',
				'prefix',
				'version'
			)
            ->get();

		return view('provider-prefix.index', compact(
			'getProvider',
			'getProviderPrefix'
		));
    }
    public function delete($id){
		$delete = ProviderPrefix::find($id);
		$delete->delete();
		return redirect()->route('provider-prefix.index')
			->with('berhasil', 'Berhasil menghapus Provider Prefix ');
    }
    public function store(Request $request){
		$message = [
			'provider_id.required' => 'mohon isi',
			'prefix.required' => 'mohon isi',
			'prefix.unique' => 'Prefix ini sudah ada',
			'prefix.numeric' => 'Prefix harus nomer',
			'prefix.digits_between' => 'Prefix harus 1 sampai 18 digit',
		];

		$validator = Validator::make($request->all(), [
			'provider_id' => 'required',
			'prefix' => 'required|unique:sw_provider_prefix|numeric|digits_between:1,18',
		], $message);

		if($validator->fails()){
			return redirect()->route('provider-prefix.index')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$save = new ProviderPrefix;
			$save->provider_id		= $request->provider_id;
			$save->prefix			= $request->prefix;
			$save->version 			= 1;
			$save->create_user_id	= Auth::user()->id;
			$save->create_datetime	= Carbon::now()->format('YmdHis');
			$save->update_user_id	= -99;/*Auth::user()->id*/
			$save->update_datetime	= '';
			$save->save();
		});

		return redirect()->route('provider-prefix.index')
			->with('alret', 'alert-success')
			->with('berhasil', 'Berhasil Menambahkan Provider Prefix '.$request->prefix);
    }
    public function update(Request $request){
		$message = [
			'provider_id.required' => 'mohon isi',
			'prefix.required' => 'mohon isi',
			'prefix.unique' => 'Prefix ini sudah ada',
			'prefix.numeric' => 'Prefix harus nomer',
			'prefix.digits_between' => 'Prefix harus 3 sampai 5 digit',
		];

		$validator = Validator::make($request->all(), [
			'provider_id' => 'required',
			'prefix' => 'required|unique:amd_provider_prefixes|numeric|digits_between:3,5',
		], $message);

		if($validator->fails()){
			return redirect()->route('provider-prefix.index')
				->withErrors($validator)->withInput()->with('update-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$update = ProviderPrefix::find($request->provider_id);
			$update->provider_id	= $request->provider_id;
			$update->prefix			= $request->prefix;
			$update->version 		= '1';
			$update->update_user_id	= '1'/*Auth::user()->id*/;
			$update->update();
		});

		return redirect()->route('provider-prefix.index')
			->with('berhasil', 'Berhasil Memperbarui Provider Prefix'.$request->prefix);
    }
}
