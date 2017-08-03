<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Agent;

class AgentController extends Controller
{
    function indexJs(){
		$getData = Agent::get();
		return view('agent.index-js', compact('getData'));
	}

	function indexPhp(){
		return view('agent.index-php');
	}

	function getDataTables(){
		return Datatables::of(
				Agent::select(
					'agent_name',
					'phone_number',
					'address',
					'city'
				)
				->get()
			)
			->make(true);
	}

	function edit($id){
		return view('agent.ubah');
	}

	function update(){

	}

	function seedTables(){
		$faker 		= Faker\Factory::create();
		$arrCity	= array('Aceh', 'Bali', 'Bangka Belitung', 'Banten', 'Bengkulu', 'Gorontalo', 'Jakarta', 'Jambi', 'Bandung', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Banjar', 'Magelang', 'Pekalongan', 'Purwokerto', 'Salatiga', 'Semarang', 'Surakarta', 'Tegal', 'Batu', 'Blitar', 'Kediri', 'Madiun', 'Malang', 'Mojokerto', 'Pasuruan'
		);

		for($i=0; $i<=100; $i++){
			$phone = '08'.rand(10,99).rand(10000000,99999999);

  			$save = new App\Models\Agent;
  			$save->agent_id  			= rand(0,99999999);
  			$save->agent_name  			= $faker->name;
  			$save->phone_number  		= $phone;
  			$save->address  			= $faker->address;
  			$save->city  				= $arrCity[rand(0,30)];
  			$save->channel_user_id  	= rand(10,50);
  			$save->channel_chat_id  	= rand(10,50);
  			$save->paloma_member_code  	= rand(10,50);
  			$save->client_id  			= 0;
  			$save->version  			= 0;
  			$save->create_user_id  		= 0;
  			$save->create_datetime  	= $faker->dateTime;
  			$save->update_user_id  		= 0;
  			$save->update_datetime  	= $faker->dateTime;
			$save->save();
		}
	}
}
