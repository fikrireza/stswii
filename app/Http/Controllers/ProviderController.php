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


    public function index()
    {
        $getProvider = Provider::select(['provider_id','provider_code','provider_name'])->get();

        return view('provider.index', compact('newProvCode','getProvider'));
    }

    public function ajaxView($id)
    {
    	$getProvider = Provider::select('provider_id','provider_code','provider_name')
                        			->where('provider_id', $id)
                        			->first();

  		$getProviderPrefix = ProviderPrefix::select('provider_id','provider_prefix_id','prefix','version')
                                			->where('provider_id', $id)
                                      ->get();

  		if($getProvider == null){
  			$view = '<h3>Provider Not Found!</h3>';
  		}
  		else{
        if (count($getProviderPrefix) == 0) {
        	$view = '<h3>Tidak Ditemukan Prefix Pada Provider Ini!</h3>';
        }
        else{
	        $view = view('provider.view',compact('getProvider', 'getProviderPrefix'))->render();
        }
  		}

      return response()->json(['html'=>$view]);
    }

    public function delete($id, $version)
    {
    		$delete = Provider::find($id);

    		if($delete == null)
        {
    			$info = 'Provider gagal dihapus! Tidak dapat menemukan Provider!';
    			$alret = 'alert-danger';
    		}
    		else if($delete->version != $version)
        {
    			$User = User::find($delete->update_user_id);
    			$info = 'Provider gagal dihapus! Provider telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
    			$alret = 'alert-danger';
    		}
    		else
        {
    			$info = 'Berhasil menghapus Provider '.$delete->prefix;
    			$alret = 'alert-success';
    			$delete->delete();
    		}

    		return redirect()->route('provider.index')->with('alret', $alret)->with('berhasil', $info);
    }

    public function store(Request $request)
    {

  		$message = [
        'provider_name.required' => 'This field required',
  			'provider_name.max' => 'Too Long, Max 25 Character',
  			'provider_name.unique' => 'This Provider Name has already taken',
        'provider_code.required' => 'This field required',
  			'provider_code.unique' => 'This Provider Code has already taken',
  		];

  		$validator = Validator::make($request->all(), [
  			'provider_code' => 'required|unique:sw_provider',
  			'provider_name' => 'required|unique:sw_provider|max:25',
  		], $message);

  		if($validator->fails()){
  			return redirect()->route('provider.index')
  				->withErrors($validator)->withInput()->with('add-false', 'Something Errors');;
  		}

  		DB::transaction(function () use($request) {
  			$save = new Provider;
  			$save->provider_code	= strtoupper($request->provider_code);
  			$save->provider_name	= strtoupper($request->provider_name);
  			$save->version 			= 0;
  			$save->create_user_id	= Auth::user()->id;
  			$save->create_datetime	= Carbon::now()->format('YmdHis');
  			$save->update_user_id	= 0;/*Auth::user()->id*/
  			$save->update_datetime	= '';
  			$save->save();
  		});

  		return redirect()->route('provider.index')
                      ->with('alret', 'alert-success')
                			->with('berhasil', 'Your data has been successfully saved '.$request->provider_name);
    }

    public function update(Request $request)
    {
    		$message = [
    			'provider_name.max' => 'Too Long, Max 25 Character',
    			'provider_name.unique' => 'This Provider Name has already taken',
    		];

    		$validator = Validator::make($request->all(), [
    			'provider_name' => 'required|max:25|unique:sw_provider,provider_name,'.$request->provider_id.',provider_id',
    			'provider_code' => 'required|max:25|unique:sw_provider,provider_code,'.$request->provider_id.',provider_id',
    		], $message);

    		if($validator->fails()){
    			return redirect()->route('provider.index')
    				->withErrors($validator)->withInput()->with('update-false', 'Something Errors');;
    		}

    		$update = Provider::find($request->provider_id);

    		if($update == null)
        {
    			$info = 'Provider gagal diupdate! Tidak dapat menemukan Provider!';
    			$alret = 'alert-danger';
    		}
    		else if($update->version != $request->version)
        {
    			$User = User::find($update->update_user_id);
    			$info = 'Provider gagal diupdate! Provider telah diupdate Oleh '.$User->name;
    			$alret = 'alert-danger';
    		}
    		else
        {
    			$info = 'Berhasil Memperbarui Provider '.$request->provider_name;
    			$alret = 'alert-success';
    			DB::transaction(function () use($request, $update) {
    				$update->increment('version');
    				$update->provider_code	= strtoupper($request->provider_code);
    				$update->provider_name	= strtoupper($request->provider_name);
    				$update->update_user_id	= Auth::user()->id;
    				$update->update_datetime= Carbon::now()->format('YmdHis');
    				$update->update();
    			});
    		}

    		return redirect()->route('provider.index')
                  			->with('alret', $alret)
                  			->with('berhasil', $info);
    }

    public function yajraGetData()
    {

    	$getProviders = Provider::select(['provider_id','provider_code','provider_name','version', DB::raw('(select count(provider_prefix_id) from sw_provider_prefix where sw_provider.provider_id = sw_provider_prefix.provider_id) as count_provider_prefix')])->get();

    	$start=1;
        return Datatables::of($getProviders)
          ->addColumn('slno', function ($getProvider) use (&$start) {
              return $start++;
          })
          ->addColumn('action', function ($getProvider) {
          	$actionHtml = '';
          	if (Auth::user()->can('read-provider'))
            { // harusnya read provider prefix
          		$actionHtml = $actionHtml."<a class='read' data-id='".$getProvider->provider_id."' ";
          		if($getProvider->count_provider_prefix!= 0)
              {
          			$actionHtml = $actionHtml."data-toggle='modal' data-target='.modal-form-read' ";
          		}

              	$actionHtml = $actionHtml."><span class='btn btn-xs btn-info btn-sm ";

              	if($getProvider->count_provider_prefix == 0)
                {
              		$actionHtml = $actionHtml."disabled";
              	}

          		$actionHtml = $actionHtml."' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-archive'></i></span></a>";
    				}
    				if (Auth::user()->can('update-provider')) {
    					$actionHtml = $actionHtml." <a class='update' data-id='".$getProvider->provider_id."' data-code='".$getProvider->provider_code."' data-name='".$getProvider->provider_name."' data-version='".$getProvider->version."' data-toggle='modal' data-target='.modal-form-update'><span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></span></a>";
    				}
    				if (Auth::user()->can('delete-provider')) {
    					$actionHtml = $actionHtml."<a href='' class='delete' data-value='".$getProvider->provider_id."'data-version='".$getProvider->version."' data-toggle='modal' data-target='.modal-delete'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></span></a>";
    				}

            return $actionHtml;
            })
            ->removeColumn('provider_id')
            ->removeColumn('version')
            ->removeColumn('count_provider_prefix')
            ->make(true);
    }

}
