<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Agent;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;

class AgentController extends Controller
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
  		$getData = Agent::get();

      return view('agent.index');
  	}

  	function getDataTables(){
  		$index = Agent::select(
          'agent_id',
          'agent_name',
          'phone_number',
          'address',
          'city',
          'active',
          'version'
        )
      ->get();

      $start=1;
  		return Datatables::of($index)
        ->addColumn('slno', function ($index) use (&$start) {
            return $start++;
        })
				->addColumn('action', function($index) {
					$html = '';
					if (Auth::user()->can('update-agent')) {
						$html .=
						"
						<a 
              class=\"update\" 
              data-id='".$index->agent_id."' 
              data-name='".$index->agent_name."' 
              data-phone='".$index->phone_number."' 
              data-address='".$index->address."' 
              data-city='".$index->city."' 
              data-version='".$index->version."' 
              data-toggle='modal' data-target='.modal-form-update'
            >
							<span class=\"btn btn-xs btn-warning btn-sm\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Update\"><i class=\"fa fa-pencil\"></i></span>
						</a>
						";
					}
					return $html;
                })
        ->addColumn('status', function($index) {
          if($index->active == 'Y'){
            return "
              <a 
                href='' 
                class='unpublish' 
                data-value='".$index->agent_id."' 
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
                data-value='".$index->agent_id."' 
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
        })
        ->removeColumn('version')
        ->escapeColumns(['*'])
				->make(true);
  	}

  	function edit($id){
  		return view('agent.edit');
  	}

  	function update(Request $request){
  		$message = [
  			// 'agent_name.required' => 'This field required',
  			// 'phone_number.required' => 'This field required',
  			'address.required' => 'This field required',
  			'city.required' => 'This field required',
  		];

  		$validator = Validator::make($request->all(), [
  			// 'agent_name' => 'required',
  			// 'phone_number' => 'required',
  			'address' => 'required',
  			'city' => 'required',
  		], $message);

  		if($validator->fails())
  		{
  			return redirect()->route('agent.index', ['id' => $request->agent_id])->withErrors($validator)->withInput()->with('update-false', 'Something Errors');
  		}

  		$index = Agent::where('agent_id', $request->agent_id)->first();

  		if($index->version != $request->version)
  		{
  			return redirect()->route('agent.index')->with('update-false', 'Your data already updated by ' . $index->updatedBy->name . '.');
  		}

  		// $index->agent_name   = $request->agent_name;
  		// $index->phone_number = $request->phone_number;
  		$index->address      = $request->address;
  		$index->city         = $request->city;

  		$index->version += 1;
  		$index->update_datetime = date('YmdHis');
  		$index->update_user_id = Auth::id();

  		$index->save();

  		return redirect()->route('agent.index')->with('berhasil', 'Your data has been successfully updated.');
  	}

    public function active($id, $version, $status){
      $call = Agent::find($id);

      if($call == null){
        $info = 'Failed to update';
        $alret = 'alert-danger';
      }
      else if($call->version != $version){
        $User = User::find($call->update_user_id);
        $info = 'Failed to update already updeted by '.$User->name;
        $alret = 'alert-danger';
      }
      else{
        $info = 'Success '.$call->agent_name;
        $alret = 'alert-success';
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
      return redirect()->route('agent.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

  	// function seedTables(){
  	// 	$faker 		= Faker\Factory::create();
  	// 	$arrCity	= array('Aceh', 'Bali', 'Bangka Belitung', 'Banten', 'Bengkulu', 'Gorontalo', 'Jakarta', 'Jambi', 'Bandung', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Banjar', 'Magelang', 'Pekalongan', 'Purwokerto', 'Salatiga', 'Semarang', 'Surakarta', 'Tegal', 'Batu', 'Blitar', 'Kediri', 'Madiun', 'Malang', 'Mojokerto', 'Pasuruan'
  	// 	);
    //
  	// 	for($i=0; $i<=100; $i++){
  	// 		$phone = '08'.rand(10,99).rand(10000000,99999999);
    //
    // 			$save = new App\Models\Agent;
    // 			$save->agent_id  			= rand(0,99999999);
    // 			$save->agent_name  			= $faker->name;
    // 			$save->phone_number  		= $phone;
    // 			$save->address  			= $faker->address;
    // 			$save->city  				= $arrCity[rand(0,30)];
    // 			$save->channel_user_id  	= rand(10,50);
    // 			$save->channel_chat_id  	= rand(10,50);
    // 			$save->paloma_member_code  	= rand(10,50);
    // 			$save->client_id  			= 0;
    // 			$save->version  			= 0;
    // 			$save->create_user_id  		= 0;
    // 			$save->create_datetime  	= $faker->dateTime;
    // 			$save->update_user_id  		= 0;
    // 			$save->update_datetime  	= $faker->dateTime;
  	// 		$save->save();
  	// 	}
  	// }
}
