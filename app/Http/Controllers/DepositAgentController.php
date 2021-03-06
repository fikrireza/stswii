<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\Notification;
use App\Models\Agent;
use Hash;
use Auth;

class DepositAgentController extends Controller
{

    //----------- Start Agent Confirm ---------//
    public function indexConfirm()
    {

        return view('deposit-agent.getUniqueCode');
    }

    public function getUniqueCode(Request $request)
    {
        $uniqueCode = $request->uniqueCode;

        try {
          ini_set('max_execution_time', 300);
          $client = new \GuzzleHttp\Client();
          $res = $client->request('GET', env('WALLET_HOST','http://localhost:9020').'/getUnconfirmedUniqueCodes?uniqueCode='.$uniqueCode)
          ->getbody();

          $result = json_decode($res);       

          if($result->status != 'OK'){
            $response = 'Failed Grab Data';
          }

        } catch (\Exception $e) {
          $response = 'Status Server '.$e->getResponse()->getStatusCode();

          return redirect()->route('deposit-agent-confirm.index')->with('gagal', $response);
        }

        $prosesUniqueCode = $result->payload;
        return view('deposit-agent.getUniqueCode', compact('prosesUniqueCode', 'uniqueCode'));
    }

    public function confirm(Request $request)
    {
      try {
        ini_set('max_execution_time', 300);
        $client = new \GuzzleHttp\Client([
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                      ]);

        $res = $client->request('POST',env('WALLET_HOST','http://localhost:9020').'/walletTopupWithCode', [
                            'json' => [
                              'clientId' => $request->clientId,
                              'uniqueCode'  => $request->uniqueCode,
                              'uniqueCodeDate'  => $request->uniqueCodeDate,
                              'amount'  => str_replace('.','',$request->amount),
                            ]
                          ])->getbody();

        $result = json_decode($res);                
        
        if ($result->status == 'OK') {
          $payload = $result->payload;  

          if($payload->status == 0){          

            //nilai sebelum perubahan
            $currentBalance = $payload->currentBalance;
            //nilai setelah perubahan
            $afterCut = $payload->afterCut;
            //nilai perubahan
            $numCut = $payload->numCut;

            $message = "Selamat, Deposit yang anda lakukan sudah berhasil.\n".
                        "Saldo Anda : $currentBalance\n".
                        "Jumlah Deposit : $numCut\n".
                        "Saldo Setelah Deposit : $afterCut";

            $agent = Agent::where('client_id', $payload->clientId)->first();
            $notification = new Notification();
            $notification->agent_id         = $agent->agent_id;
            $notification->title            = 'Top up Deposit';
            $notification->message          = $message;
            $notification->status           = 'D';
            $notification->create_datetime  = date('YmdHis');
            $notification->create_user_id   = Auth::id();
            $notification->update_datetime  = '';
            $notification->update_user_id   = -99;
            $notification->version          = 0;
            $notification->save();

            $response = 'Sukses';
          }elseif($payload->status == 1){
            $response = 'Client Id Not Found';
          }elseif($payload->status == 2){
            $response = 'Amount Not Valid';
          }else{
            $response = 'Unique Code Not Valid';
          }

        }else{
          $response = 'Gagal '+$errorKey;
        }  

      } catch (\Exception $e) {  			                
        $response = 'Oooppsss..... Status Server '.$e->getResponse()->getStatusCode();        
  			return redirect()->route('deposit-agent-confirm.index')->with('gagal', $response);
      }


      return redirect()->route('deposit-agent-confirm.index')->with('hasil', $response);

    }
    //----------- End Agent Confirm ---------//

    //----------- Start Agent Void ----------//
    public function void()
    {

        return view('deposit-agent.void');
    }

    public function getRangeDate(Request $request)
    {
        $startDate = date('Ymd', strtotime($request->startDate));
        $endDate = date('Ymd', strtotime($request->endDate));
        $limit = (int)100;
        $offset = (int)0;

        $query = '?startDate='.$startDate.'&endDate='.$endDate.'&limit='.$limit.'&offset='.$offset;

        try {
          ini_set('max_execution_time', 300);
          $client = new \GuzzleHttp\Client();
          $res = $client->request('GET', env('WALLET_HOST','http://localhost:9020').'/getConfirmedTopUp'.$query)
                        ->getbody();

          \Log::debug($res);
          $result = json_decode($res);
          
          if($result->status == 'OK'){
            $response = 'Sukses';
          }else{
            $response = 'Error';
          }
        }
        catch (\Exception $e)
        {
    			$response = 'Oooppsss..... Status Server '.$e->getResponse()->getStatusCode();

    			return redirect()->route('deposit-agent-reversal.index')->with('gagal', $response);
        }

        $proses = $result->payload;
        return view('deposit-agent.void', compact('proses', 'startDate', 'endDate'));
    }

    public function reversalTrx(Request $request)
    {
        // Check Administrator Password
        $checkPassword = User::where('name', $request->name)->first();

        if(!$checkPassword){
          return redirect()->route('deposit-agent-reversal.index')->with('gagal', 'User did not authorize this action');
        }

        $passwordHash = false;
        if($checkPassword->can('confirm-deposit-reversal')){
          $passwordHash = Hash::check($request->password,$checkPassword->password);
        }

        if($passwordHash == false){
          return redirect()->route('deposit-agent-reversal.index')->with('gagal', 'User did not authorize this action');
        }
        // Check Administrator Password


        try {
          ini_set('max_execution_time', 300);
          $client = new \GuzzleHttp\Client([
                                            'headers' => [
                                                'Accept' => 'application/json',
                                                'Content-Type' => 'application/json',
                                            ],
                                          ]);

          $res = $client->request('POST',env('WALLET_HOST','http://localhost:9020').'/reversal-trx', [
                              'json' => [
                                'clientId' => (int)$request->clientId,
                                'refDocNo'  => $request->refNo,
                                'billerId'  => -99,
                                'docTypeId'  => -99,
                                'acquirerId'  => -99,
                              ]
                            ])->getbody();

          $result = json_decode($res);
        
          if ($result->status == 'OK') {
            $payload = $result->payload;
            if($payload->status == 0){
              $response = 'Sukses';
            }else{
              $response = 'Gagal';
            }
          }else{
            $response = 'Gagal '.$result->errorKey;
          }          

        } catch (\Exception $e) {
            $response = 'Oooppsss..... Status Server '.$e->getResponse()->getStatusCode();

            return redirect()->route('deposit-agent-reversal.index')->with('gagal', $response);
        }


        return redirect()->route('deposit-agent-reversal.index')->with('hasil', $response);
    }


    //----------- Start Agent All Unconfirm ---------//
    public function indexUnconfirm()
    {
        try{
          ini_set('max_execution_time', 300);
          $client = new \GuzzleHttp\Client();
          $res = $client->request('GET', env('WALLET_HOST','http://localhost:9020').'/getAllUnconfirmedUniqueCodes')
          ->getbody();

          $result = json_decode($res);    
                                   
          if($result->status != 'OK'){
              $response = 'Tidak ada data';
            }
        }
        catch (\Exception $e)
        {
          abort(503);
        }
        $prosesUniqueCode = $result->payload;
        return view('deposit-agent.indexUnconfirm', compact('prosesUniqueCode'));
    }

    public function postUnconfirm(Request $request)
    {
        try {
          ini_set('max_execution_time', 300);
          $client = new \GuzzleHttp\Client([
                                            'headers' => [
                                                'Accept' => 'application/json',
                                                'Content-Type' => 'application/json',
                                            ],
                                          ]);

          $res = $client->request('POST',env('WALLET_HOST','http://localhost:9020').'/walletTopupWithCode', [
                              'json' => [
                                'clientId' => $request->clientId,
                                'uniqueCode'  => $request->uniqueCode,
                                'uniqueCodeDate'  => $request->uniqueCodeDate,
                                'amount'  => str_replace('.','',$request->amount),
                              ]
                            ])->getbody();
          
          $result = json_decode($res);          

          if ($result->status == 'OK') {
            $payload = $result->payload;
            
            if($payload->status == 0){            

              //nilai sebelum perubahan
              $currentBalance = $payload->currentBalance;
              //nilai setelah perubahan
              $afterCut = $payload->afterCut;
              //nilai perubahan
              $numCut = $payload->numCut;

              $message = "Selamat, Deposit yang anda lakukan sudah berhasil.\n".
                        "Saldo Anda : $currentBalance\n".
                        "Jumlah Deposit : $numCut\n".
                        "Saldo Setelah Deposit : $afterCut";

              $agent = Agent::where('client_id', $payload->clientId)->first();
              $notification = new Notification();
              $notification->agent_id         = $agent->agent_id;
              $notification->title            = 'Top up Deposit';
              $notification->message          = $message;
              $notification->status           = 'D';
              $notification->create_datetime  = date('YmdHis');
              $notification->create_user_id   = Auth::id();
              $notification->update_datetime  = '';
              $notification->update_user_id   = -99;
              $notification->version          = 0;
              $notification->save();

              $response = 'Sukses Confirm '.$request->uniqueCode;
            }elseif($payload->status == 1){
              $response = 'Client Id Tidak ditemukan';
            }elseif($payload->status == 2){
              $response = 'Amount Tidak Valid';
            }else{
              $response = 'Unique Code Tidak Valid';
            }
          }else{
            $response = 'Gagal '+$errorKey;
          }
          
        } catch (\Exception $e) {
            $response = 'Status Server '.$e->getResponse()->getStatusCode();

            return redirect()->route('deposit-agent-unconfirm.index')->with('gagal', $response);
        }

        return redirect()->route('deposit-agent-unconfirm.index')->with('hasil', $response);
    }
    //----------- End Agent All Unconfirm ---------//



    // Just for localhost development
    public function getUnconfirmedUniqueCodes(Request $request)
    {
        $uniqueCode = $request->query('uniqueCode');

        $getUniqueCode = [
                          [
                            'clientId' => "1122",
                            'name'  => "Agent Name 1",
                            'uniqueCode'  => "abcde1234",
                            'uniqueCodeDate' => "20170101",
                          ],
                          [
                            'clientId' => "112233",
                            'name'  => "Agent Name 2",
                            'uniqueCode'  => "abcde1234",
                            'uniqueCodeDate' => "20171212",
                          ],
                          [
                            'clientId' => "12345",
                            'name'  => "Agent Name 3",
                            'uniqueCode'  => "abcde1234",
                            'uniqueCodeDate' => "20171212",
                          ]
                        ];

        $json = ['status' => 0, 'uniqueCodeList' => $getUniqueCode];

        return response()->json($json);
    }

    public function getConfirmedTopUp(Request $request)
    {
      $startDate = $request->query('startDate');
      $endDate = $request->query('endDate');
      $limit = $request->query('limit');
      $offset = $request->query('offset');

      $getConfirmTopup = [
                          [
                            "clientId" => "12345678",
                            "refNo" => "12345678",
                            "billerId" => "1234568",
                            "docTypeId" => "12345678",
                            "acquirerId" => "12345678",
                            "name" => "Agent 1",
                            "amount" => "1000000",
                            "uniqueCode" => "a1b2c3d4",
                            "uniqueCodeDate" => "20170101",
                            "confirmDate" => "20170101",
                          ],[
                            "clientId" => "12345678",
                            "refNo" => "12345678",
                            "billerId" => "12345678",
                            "docTypeId" => "12345678",
                            "acquirerId" => "12345678",
                            "name" => "Agent 2",
                            "amount" => "10800000",
                            "uniqueCode" => "abcedf5678",
                            "uniqueCodeDate" => "20171201",
                            "confirmDate" => "20170101",
                          ],[
                            "clientId" => "12345678",
                            "refNo" => "12345678",
                            "billerId" => "12345678",
                            "docTypeId" => "12345678",
                            "acquirerId" => "12345678",
                            "name" => "Agent 3",
                            "amount" => "2300000",
                            "uniqueCode" => "f6g7h8i9j0",
                            "uniqueCodeDate" => "20170808",
                            "confirmDate" => "20170101",
                          ],[
                            "clientId" => "12345678",
                            "refNo" => "12345678",
                            "billerId" => "12345678",
                            "docTypeId" => "12345678",
                            "acquirerId" => "12345678",
                            "name" => "Agent 4",
                            "amount" => "4500000",
                            "uniqueCode" => "6f7g8h9i0j",
                            "uniqueCodeDate" => "20171010",
                            "confirmDate" => "20170101",
                          ],[
                            "clientId" => "12345678",
                            "refNo" => "12345678",
                            "billerId" => "12345678",
                            "docTypeId" => "12345678",
                            "acquirerId" => "12345678",
                            "name" => "Agent 5",
                            "amount" => "2000000",
                            "uniqueCode" => "1a2b3c4d5e",
                            "uniqueCodeDate" => "20170101",
                            "confirmDate" => "201710101",
                          ]
                          ];

      $json = ['status' => 0, 'topupList' => $getConfirmTopup];

      return response()->json($json);
    }
    // Just for localhost development

}
