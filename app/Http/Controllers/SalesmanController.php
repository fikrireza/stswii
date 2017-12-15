<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Yajra\Datatables\Facades\Datatables;
use App\Models\Sales;
use App\Models\User;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;

class SalesmanController extends Controller
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

    function index(){
      return view('salesman.index');
  	}

  	function getSalesmanList(){
  		  		
  		$index = Sales::join('wa_users', 'wa_users.id', 'sw_sales.user_id')
				->select([
					'sales_id', 
					'name', 
					'limit_deposit', 
					'sw_sales.active as active', 
					'sw_sales.version as version'
				])
        ->orderBy('name', 'asc')
        ->get();		
		
		    $start      = 1;
        $Datatables = Datatables::of($index)
        	->addColumn('slno', function ($getData) use (&$start) {
                return $start++;
            })
            ->editColumn('limit_deposit', function($getData){
            	return 'Rp. ' . number_format($getData->limit_deposit, 2);
            })
            ->addColumn('action', function($getData){
            	$actionHtml = '';
            	if (Auth::user()->can('update-salesman')) {
            		$actionHtml = $actionHtml."<a href='' class='update' 
		            	data-sales-id='".$getData->sales_id."'
		            	data-limit-deposit='".$getData->limit_deposit."'
		            	data-version='".$getData->version."'
		            	data-toggle='modal' data-target='.modal-form-update'><span class='btn btn-warning btn-xs btn-sm' data-toggle='tooltip' data-placement='top' title='Edit Sales'><i class='fa fa-edit'></i></span></a>";
            	}
            	
            	return $actionHtml;
            });
            if (Auth::user()->can('activate-salesman')) {
            	$Datatables = $Datatables->editColumn('active', function($index) {
	            if($index->active == 'Y'){
	              return "
	                <a 
	                  href='' 
	                  class='unpublish' 
	                  data-value='".$index->sales_id."' 
	                  data-version='".$index->version."' 
	                  data-toggle='modal' 
	                  data-target='.modal-nonactive'
	                >
	                  <span 
	                    class='label label-success' 
	                    data-toggle='tooltip' 
	                    data-placement='top' 
	                    title='Active'
	                  >Active</span>
	                </a><br>";
	            }
	            else if($index->active == 'N'){
	              return "
	                <a 
	                  href='' 
	                  class='publish' 
	                  data-value='".$index->sales_id."' 
	                  data-version='".$index->version."' 
	                  data-toggle='modal' 
	                  data-target='.modal-active'
	                >
	                  <span 
	                    class='label label-danger' 
	                    data-toggle='tooltip' 
	                    data-placement='top' 
	                    title='Non Active'
	                  >Non Active</span>
	                </a><br>";
	            }
	          });
            }
            
			$Datatables = $Datatables
				->removeColumn('version')
	            ->escapeColumns(['*'])
	            ->make(true);

	        return $Datatables;
		
  	}

    function add(){
      $user = DB::table('wa_users')
        ->whereNotExists(function($query){
          $query->select(DB::raw(1))
            ->from('sw_sales')
            ->whereRaw('sw_sales.user_id = wa_users.id');
        })->get();
      
      return view('salesman.tambah')->with('users', $user);
    }

    public function store(Request $request)
    {
      $message = [
        'user_id.required' => 'This field required',
        'limit_deposit.required' => 'This field required'     
      ];

      $validator = Validator::make($request->all(), [
        'user_id' => 'required',
        'limit_deposit' => 'required'
      ], $message);

      if($validator->fails())
      {
        return redirect()->route('salesman.add')->withErrors($validator)->withInput();
      }

      $index = new Sales();

      $index->user_id  = $request->user_id;
      $index->limit_deposit  = str_replace('.','',$request->limit_deposit);

      $index->active = isset($request->active) ? "Y" : "N";

      if(isset($request->active))
      {
        $index->active_datetime = date('YmdHis');
        $index->non_active_datetime = '';
      }
      else
      {
        $index->active_datetime = '';
        $index->non_active_datetime = date('YmdHis');
      }

      $index->version = 0;
      $index->create_datetime = date('YmdHis');
      $index->create_user_id = Auth::id();
      $index->update_datetime = '';
      $index->update_user_id = -99;

      $index->save();

      return redirect()->route('salesman.index')->with('berhasil', 'Your data has been successfully saved.');
    }

  	function edit($id){
  		return view('salesman.edit');
  	}  

  	function update(Request $request){  		
  		$message = [
  			'limit_deposit.required' => 'This field required'
  		];

  		$validator = Validator::make($request->all(), [  			
  			'limit_deposit' => 'required'
  		], $message);

  		if($validator->fails())
  		{
  			return redirect()->route('salesman.index', ['id' => $request->sales_id])->withErrors($validator)->withInput()->with('update-false', 'Something Errors');
  		}

  		$index = Sales::where('sales_id', $request->sales_id)->first();

  		if($index->version != $request->version)
  		{
  			return redirect()->route('salesman.index')->with('update-false', 'Your data already updated by ' . $index->updatedBy->name . '.');
  		}

  		$index->limit_deposit      = str_replace('.','',$request->limit_deposit);
  		$index->version += 1;
  		$index->update_datetime = date('YmdHis');
  		$index->update_user_id = Auth::id();

  		$index->save();

  		return redirect()->route('salesman.index')
  			->with('alert', 'alert-success')
  			->with('berhasil', 'Your data has been successfully updated.');
  	}

  	public function active($id, $version, $status){
      $call = Sales::find($id);

      if($call == null){
        $info = 'Failed to update';
        $alert = 'alert-danger';
      }
      else if($call->version != $version){
        $User = User::find($call->update_user_id);
        $info = 'Failed to update already updeted by '.$User->name;
        $alert = 'alert-danger';
      }
      else{
        $info = 'Success ';
        $alert = 'alert-success';
        DB::transaction(function () use($call, $status) {
          $call->increment('version');
          $call->active         = $status;
          $call->update_user_id = Auth::user()->id;
          $call->update_datetime= Carbon::now()->format('YmdHis');
          if($status == 'Y'){
            $call->active_datetime  = Carbon::now()->format('YmdHis');
          }
          else if($status == 'N'){
            $call->non_active_datetime  = Carbon::now()->format('YmdHis');
          }
          $call->update();
        });
      }
      return redirect()->route('salesman.index')
        ->with('alert', $alert)
        ->with('berhasil', $info);
    }

}
