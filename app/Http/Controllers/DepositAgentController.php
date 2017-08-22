<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use Hash;

class DepositAgentController extends Controller
{

    public function indexConfirm()
    {

        return view('deposit-agent.indexConfirm');
    }

    public function getUniqueCode(Request $request)
    {
        $uniqueCode = $request->uniqueCode;

        ini_set('max_execution_time', 300);
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://localhost/getUnconfirmedUniqueCodes?uniqueCode='.$uniqueCode)
                      ->getbody();

        $prosesUniqueCode = json_decode($res);

        if($prosesUniqueCode->status != 0){
          abort(404);
        }

        return view('deposit-agent.indexConfirm', compact('prosesUniqueCode', 'uniqueCode'));
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

        $res = $client->request('POST','http://localhost/walletTopupWithCode', [
                            'json' => [
                              'clientId' => $request->clientId,
                              'uniqueCode'  => $request->uniqueCode,
                              'uniqueCodeDate'  => $request->uniqueCodeDate,
                              'amount'  => $request->amount,
                            ]
                          ])->getbody();

        $result = json_decode($res);

        if($result->status == 0){
          $response = 'Sukses';
        }elseif($result->status == 1){
          $response = 'Client Id Tidak ditemukan';
        }elseif($result->status == 2){
          $response = 'Amount Tidak Valid';
        }else{
          $response = 'Unique Code Tidak Valid';
        }

      } catch (Exception $e) {

      }


      return redirect()->route('deposit-agent-confirm.index')->with('hasil', $response);

    }


    public function indexConfirmTopUp()
    {

        return view('deposit-agent.indexConfirmTopUp');
    }

    public function getRangeDate(Request $request)
    {
        $startDate = date('Ymd', strtotime($request->startDate));
        $endDate = date('Ymd', strtotime($request->endDate));
        $limit = 100;
        $offset = $request->offset;

        $query = '?startDate='.$startDate.'&endDate='.$endDate.'&limit='.$limit.'&offset='.$offset;

        ini_set('max_execution_time', 300);
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://localhost/getConfirmedTopUp'.$query)
                      ->getbody();

        $proses = json_decode($res);

        if($proses->status != 0){
          abort(404);
        }

        return view('deposit-agent.indexConfirmTopUp', compact('proses', 'startDate', 'endDate', 'limit', 'offset'));
    }

    public function reversalTrx(Request $request)
    {
        // Check Administrator Password
        // $password = Hash::make($request->password);
        $checkPassword = Role::with('users')->where('slug', 'like', '%administrator%')->get();

        $passwordHash = false;
        foreach ($checkPassword as $key) {
          foreach ($key->users as $user) {
            if($user->name == $request->name){
              $passwordHash = Hash::check($request->password,$user->password);
            }
          }
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

          $res = $client->request('POST','http://localhost/reversalTrx', [
                              'json' => [
                                'clientId' => $request->clientId,
                                'refNo'  => $request->refNo,
                                'billerId'  => '-99',
                                'docTypeId'  => '-99',
                                'acquirerId'  => '-99',
                              ]
                            ])->getbody();

          $result = json_decode($res);

          if($result->status == 0){
            $response = 'Sukses';
          }else{
            $response = '';
          }

        } catch (Exception $e) {

        }


        return redirect()->route('deposit-agent-reversal.index')->with('hasil', $response);
    }



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
