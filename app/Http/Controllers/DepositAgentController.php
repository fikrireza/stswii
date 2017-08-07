<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ApiUniqueCode;


class DepositAgentController extends Controller
{

    public function index()
    {

        return view('deposit-agent.index');
    }

    public function getUniqueCode(Request $request)
    {
        $uniqueCode = $request->uniqueCode;

        ini_set('max_execution_time', 300);
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://localhost/stswii/public/getUnconfirmedUniqueCodes?uniqueCode='.$uniqueCode)
                      ->getbody();

        $prosesUniqueCode = json_decode($res);

        if($prosesUniqueCode->status != 0){
          abort(404);
        }

        return view('deposit-agent.index', compact('prosesUniqueCode', 'uniqueCode'));
    }

    public function confirm(Request $request)
    {
      try {
        ini_set('max_execution_time', 300);
        $client = new \GuzzleHttp\Client();
        $res = $client->post('http://localhost/stswii/public/walletTopupWithCode', [
          'clientId' => $request->clientId,
          'uniqueCode'  => $request->uniqueCode,
          'uniqueCodeDate'  => $request->uniqueCodeDate,
          'amount'  => $request->amount
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


        return redirect()->route('deposit-agent.index')->with('hasil', $response);

      }


    // public function getUnconfirmedUniqueCodes(Request $request)
    // {
    //     $uniqueCode = $request->query('uniqueCode');
    //
    //     $getUniqueCode = ApiUniqueCode::where('uniqueCode', $uniqueCode)->get();
    //
    //     $json = ['status' => 0, 'uniqueCodeList' => $getUniqueCode];
    //
    //     return response()->json($json);
    // }

}
