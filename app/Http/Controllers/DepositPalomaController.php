<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PalomaDepositTrx;
use Validator;
use Auth;
use DB;


class DepositPalomaController extends Controller
{

    public function store(Request $request)
    {
        $checkUnique = PalomaDepositTrx::where('tenant_id', $request->tenant_id)
                                      ->where('ou_id', $request->ou_id)
                                      ->where('doc_type_id', $request->doc_type_id)
                                      ->where('doc_no', $request->doc_no)
                                      ->first();

        if($checkUnique){
          return response()->json(['errors'=> 'Unique Fields']);
        }

        $message = [
          'tenant_id.required' => 'Wajib di isi',
          'tenant_id.unique' => 'Tenant Id sudah ada',
          'ou_id.required' => 'Wajib di isi',
    			'ou_id.unique' => 'Ou Id sudah ada',
          'doc_type_id.required' => 'Wajib di isi',
    			'doc_type_id.unique' => 'Doc Type Id sudah ada',
          'doc_no.required' => 'Wajib di isi',
    			'doc_no.unique' => 'Doc No sudah ada',
          'doc_date.required' => 'Wajib di isi',
          'partner_code.required' => 'Wajib di isi',
          'deposit_amount.required' => 'Wajib di isi',
        ];

        $validator = Validator::make($request->all(),[
          'tenant_id' => 'required',
          'ou_id' => 'required',
          'doc_type_id' => 'required',
          'doc_no' => 'required',
          'doc_date' => 'required',
          'partner_code' => 'required',
          'deposit_amount' => 'required',
        ], $message);

        if($validator->fails()){
          return response()->json(['errors'=>$validator->errors()]);
        }

        $save = new PalomaDepositTrx;
        $save->tenant_id = $request->tenant_id;
        $save->ou_id = $request->ou_id;
        $save->doc_type_id = $request->doc_type_id;
        $save->doc_no = $request->doc_no;
        $save->doc_date = $request->doc_date;
        $save->partner_code = $request->partner_code;
        $save->deposit_amount = $request->deposit_amount;
        $save->status = 'D';
        $save->confirmed_user_id = '-99';
        $save->confirmed_datetime = 00000000000000;

        $save->version = 1;
        $save->create_datetime = 00000000000000;
        $save->create_user_id = '-99';
        $save->update_datetime = 00000000000000;
        $save->update_user_id = '-99';
        $save->save();

        $status = ['status' => 0];


        return response()->json($status);
    }
}
