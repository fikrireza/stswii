<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Agent;
use Auth;
use Validator;

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
  		$index = Agent::select('agent_id','agent_name','phone_number','address','city','version')->get();

  		return Datatables::of($index)
  				->addColumn('action', function($index) {
  					$html = '';
  					if (Auth::user()->can('update-agent')) {
  						$html .=
  						"
  						<a class=\"update\" data-id='".$index->agent_id."' data-name='".$index->agent_name."' data-phone='".$index->phone_number."' data-address='".$index->address."' data-city='".$index->city."' data-version='".$index->version."' data-toggle='modal' data-target='.modal-form-update'>
  							<span class=\"btn btn-xs btn-warning btn-sm\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Update\"><i class=\"fa fa-pencil\"></i></span>
  						</a>
  						";
  					}
  					return $html;
                  })
  				->make(true);
  	}

  	function edit($id){
  		return view('agent.edit');
  	}

  	function update(Request $request){
  		$message = [
  			'agent_name.required' => 'This field required',
  			'phone_number.required' => 'This field required',
  			'address.required' => 'This field required',
  			'city.required' => 'This field required',
  		];

  		$validator = Validator::make($request->all(), [
  			'agent_name' => 'required',
  			'phone_number' => 'required',
  			'address' => 'required',
  			'city' => 'required',
  		], $message);

  		if($validator->fails())
  		{
  			return redirect()->route('agent.edit', ['id' => $request->agent_id])->withErrors($validator)->withInput()->with('update-false', 'Something Errors');
  		}

  		$index = Agent::where('agent_id', $request->agent_id)->first();

  		if($index->version != $request->version)
  		{
  			return redirect()->route('agent.index')->with('update-false', 'Your data already updated by ' . $index->updatedBy->name . '.');
  		}

  		$index->agent_name   = $request->agent_name;
  		$index->phone_number = $request->phone_number;
  		$index->address      = $request->address;
  		$index->city         = $request->city;

  		$index->version += 1;
  		$index->update_datetime = date('YmdHis');
  		$index->update_user_id = Auth::id();

  		$index->save();

  		return redirect()->route('agent.index')->with('berhasil', 'Your data has been successfully updated.');
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
