<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;

use App\Models\User;
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
    public function delete($id, $version){
    	
		$delete = ProviderPrefix::find($id);
		if($delete == null){
			$info = 'Prefix gagal dihapus! Tidak dapat menemukan Prefix!';
			$alret = 'alert-danger';
		}
		else if($delete->version != $version){
			$User = User::find($delete->update_user_id);
			$info = 'Prefix gagal dihapus! Prefix telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
			$alret = 'alert-danger';
		}
		else{
			$info = 'Berhasil menghapus Prefix '.$delete->prefix;
			$alret = 'alert-success';
			$delete->delete();
		}
		return redirect()->route('provider-prefix.index')
			->with('alret', $alret)
			->with('berhasil', $info);
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
			'prefix' => '
				required|
				numeric|
				digits_between:1,18|
				unique:sw_provider_prefix,prefix,NULL,provider_prefix_id,provider_id,'.$request->provider_id
			,
		], $message);

		if($validator->fails()){
			return redirect()->route('provider-prefix.index')
				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
		}

		DB::transaction(function () use($request) {
			$save = new ProviderPrefix;
			$save->provider_id		= $request->provider_id;
			$save->prefix			= $request->prefix;
			$save->version 			= 0;
			$save->create_user_id	= Auth::user()->id;
			$save->create_datetime	= Carbon::now()->format('YmdHis');
			$save->update_user_id	= 0;/*Auth::user()->id*/
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
			'prefix' => '
				required|
				numeric|
				digits_between:1,18|
				unique:sw_provider_prefix,prefix,NULL,provider_prefix_id,provider_id,'.$request->provider_id
		], $message);

		if($validator->fails()){
			return redirect()->route('provider-prefix.index')
				->withErrors($validator)->withInput()->with('update-false', 'Something Errors');;
		}

		$update = ProviderPrefix::find($request->provider_prefix_id);
		
		if($update == null){
			$info = 'Prefix gagal diupdate! Tidak dapat menemukan Prefix!';
			$alret = 'alert-danger';
		}
		else if($update->version != $request->version){
			$User = User::find($update->update_user_id);
			$info = 'Prefix gagal diupdate! Prefix telah diupdate Oleh '.$User->name;
			$alret = 'alert-danger';
		}
		else{
			$info = 'Berhasil Memperbarui Prefix '.$request->prefix;
			$alret = 'alert-success';
			DB::transaction(function () use($request, $update) {
				$update->increment('version');
				$update->provider_id	= $request->provider_id;
				$update->prefix			= $request->prefix;
				$update->update_user_id	= Auth::user()->id;
				$update->update_datetime= Carbon::now()->format('YmdHis');
				$update->update();
			});
		}

		return redirect()->route('provider-prefix.index')
			->with('alret', $alret)
			->with('berhasil', $info);
    }
    public function yajraGetData(){

    	$getProviderPrefixs = ProviderPrefix::leftJoin('sw_provider', 'sw_provider.provider_id', 'sw_provider_prefix.provider_id')
	    	->select([
	    		'sw_provider_prefix.provider_id as provider_id',
	    		'sw_provider.provider_code as provider_code',
				'provider_prefix_id',
				'prefix',
				'sw_provider_prefix.version'
    		])
    		->get();

    	$start=1;
        return Datatables::of($getProviderPrefixs)
            ->addColumn('slno', function ($getProviderPrefix) use (&$start) {
                return $start++;
            })
            ->addColumn('action', function ($getProviderPrefix) {
            	$actionHtml = '';
				if (Auth::user()->can('update-provider-prefix')) {
					$actionHtml = $actionHtml." <a class='update' data-provider_id='".$getProviderPrefix->provider_id."' data-prefix_id='".$getProviderPrefix->provider_prefix_id."' data-prefix='".$getProviderPrefix->prefix."' data-version='".$getProviderPrefix->version."' data-toggle='modal' data-target='.modal-form-update'><span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></span></a>";
				}
				if (Auth::user()->can('delete-provider-prefix')) {
					$actionHtml = $actionHtml."<a href='' class='delete' data-value='".$getProviderPrefix->provider_prefix_id."'data-version='".$getProviderPrefix->version."' data-toggle='modal' data-target='.modal-delete'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus'><i class='fa fa-remove'></i></span></a>";
				}
                return $actionHtml;
            })
            ->removeColumn('provider_prefix_id')
            ->removeColumn('version')
            ->make(true);
    }
}
