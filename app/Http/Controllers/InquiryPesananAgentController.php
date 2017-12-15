<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Pos;
use DB;
use Yajra\Datatables\Facades\Datatables;
use Validator;
use Auth;
use Carbon\Carbon;
use Mail;
use Hash;
use Log;

class InquiryPesananAgentController extends Controller
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

    public function index(Request $request)
    {
        
        $message = [
            'f_agent_phone.numeric' => 'Invalid filter',
            'f_start_date.date'     => 'Invalid filter',
            'f_end_date.date'       => 'Invalid filter'
        ];

        $validator = Validator::make($request->all(), [
                'f_agent_phone' => 'numeric|nullable',
                'f_start_date'  => 'date|nullable',
                'f_end_date'    => 'date|nullable'
            ], $message);

        if($validator->fails())
        {
            return redirect()->route('report.inquiry-pesanan-agent-index');
        }

        return view('inquiry-pesanan-agent.index', compact('request'));
    }

    public function getMemberOrderProductList(Request $request){

        $index = DB::table('sw_agent')
                ->join('sw_pos', 'sw_pos.agent_id', '=', 'sw_agent.agent_id')
                ->leftJoin('sw_partner_product', 'sw_partner_product.partner_product_id', '=', 'sw_pos.partner_product_id')
                ->join('sw_product', 'sw_product.product_id', '=', 'sw_pos.product_id')
                ->leftJoin('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', '=', 'sw_partner_product.partner_pulsa_id')
                ->select('sw_pos.pos_id', 'sw_agent.agent_name', 'sw_agent.phone_number', 'sw_pos.purchase_datetime', 'sw_pos.gross_sell_price', 'sw_pos.receiver_phone_number', 'sw_pos.status', 'sw_pos.status_remark', 'sw_pos.version', 'sw_partner_product.partner_product_code', 'sw_product.product_code', 'sw_partner_pulsa.partner_pulsa_code');
                

        if(isset($request->f_agent_name) && $request->f_agent_name != ''){            
            $index->where(DB::raw('lower(sw_agent.agent_name)'), 'like', '%'.strtolower($request->f_agent_name).'%');
        }

        if(isset($request->f_agent_phone) && $request->f_agent_phone != ''){
            if (substr($request->f_agent_phone, 0, 2) == '62') {
                $f_agent_phone = '+'.$request->f_agent_phone;
            }else{
                $f_agent_phone = $request->f_agent_phone;
            }
            $index->where('sw_agent.phone_number', $f_agent_phone); 
        }

        if(isset($request->f_receiver_phone) && $request->f_receiver_phone != ''){
            $index->where('sw_pos.receiver_phone_number', $request->f_receiver_phone); 
        }

        if(isset($request->f_transaction_status) && $request->f_transaction_status != ''){
            $index->where('sw_pos.status', $request->f_transaction_status);
        }

        if(isset($request->f_start_date) && $request->f_start_date != '' && isset($request->f_end_date) && $request->f_end_date != ''){

            $f_start_date = date('YmdHis', strtotime($request->f_start_date.' 00:00:00'));
            $f_end_date = date('YmdHis', strtotime($request->f_end_date.' 23:59:59'));
            $index->whereBetween('sw_pos.purchase_datetime', [$f_start_date, $f_end_date]);
        }

        $index->orderBy('sw_pos.purchase_datetime', 'DESC')->get();
        
        $start      = 1;
        $Datatables = Datatables::of($index)
            ->addColumn('slno', function ($getData) use (&$start) {
                return $start++;
            })
            ->editColumn('agent_name', function ($getData) {
                return $getData->agent_name.' - '.$getData->phone_number;
            })
            ->editColumn('purchase_datetime', function ($getData) {
                return date('Y-m-d H:i:s', strtotime($getData->purchase_datetime));
            })
            ->editColumn('product_code', function($getData){
                return $getData->product_code.' - Rp. ' . number_format($getData->gross_sell_price, 2);
            })
            ->editColumn('partner_product_code', function($getData){
                return $getData->partner_product_code.' - ' .$getData->partner_pulsa_code ;
            })
            ->editColumn('status', function ($getData) {
                if ($getData->status == 'S') {
                    return "Sukses";
                } else if ($getData->status == 'I'){
                    return "Sedang Diproses";
                }else if($getData->status == 'E'){
                    return "Gagal";
                }
            })
            ->addColumn('action', function($getData){
                if ($getData->status == 'S') {
                    $transactionStatus = "Sukses";
                } else if ($getData->status == 'I'){
                    $transactionStatus = "Sedang Diproses";
                }else if($getData->status == 'E'){
                    $transactionStatus = "Gagal";
                }
                $actionHtml = '';
                $actionHtml = $actionHtml."<a class='remark transaction-detail' 
                data-value='".$getData->pos_id."'
                data-agent-name='".$getData->agent_name."'
                data-agent-phone='".$getData->phone_number."'
                data-transaction-date='".date('Y-m-d H:i:s', strtotime($getData->purchase_datetime))."'
                data-ordered-product-code='".$getData->product_code."'
                data-product-price='".number_format($getData->gross_sell_price, 2)."'
                data-destination-phone='".$getData->receiver_phone_number."'
                data-partner-product-code='".$getData->partner_product_code."'
                data-partner-code='".$getData->partner_pulsa_code."'
                data-transaction-status='".$transactionStatus."'
                data-status-remark='".$getData->status_remark."'><span class='btn btn-info btn-xs btn-sm' data-toggle='tooltip' data-placement='top' title='Detail Transaction'><i class='fa fa-archive'></i></span></a>
                <a class='detail-log' data-value='".$getData->pos_id."'><span class='btn btn-warning btn-xs btn-sm' data-toggle='tooltip' data-placement='top' title='Detail Log'><i class='fa fa-list'></i></span></a>
                ";
                return $actionHtml;
            });

            if (Auth::user()->can('set-sukses-inquiry-pesanan-agent') || Auth::user()->can('set-gagal-inquiry-pesanan-agent')) {
                $Datatables = $Datatables->addColumn('action_status', function($index){
                    $button = "";
                    if ($index->status == 'I') {
                        if (Auth::user()->can('set-sukses-inquiry-pesanan-agent')) {
                            $button .= "<a
                                    href=''
                                    class='set-sukses btn btn-success btn-xs'
                                    data-value='".$index->pos_id."'
                                    data-version='".$index->version."'
                                    data-toggle='modal'
                                    data-target='.modal-set-sukses'
                                    data-toggle='tooltip' data-placement='top' title='Set Sukses'
                                >
                                <i class='fa fa-check'></i>
                                </a>";
                        }
                        if (Auth::user()->can('set-gagal-inquiry-pesanan-agent')) {
                            $button .= "
                                <a
                                    href=''
                                    class='set-gagal btn btn-danger btn-xs'
                                    data-value='".$index->pos_id."'
                                    data-version='".$index->version."'
                                    data-toggle='modal'
                                    data-target='.modal-set-gagal'
                                    data-toggle='tooltip' data-placement='top' title='Set Gagal'
                                >
                                <i class='fa fa-remove'></i>
                                </a>";
                        }
                        
                    }else if($index->status == 'S'){
                        if (Auth::user()->can('set-gagal-inquiry-pesanan-agent')) {
                            $button .= "
                                <a
                                    href=''
                                    class='set-gagal btn btn-danger btn-xs'
                                    data-value='".$index->pos_id."'
                                    data-version='".$index->version."'
                                    data-toggle='modal'
                                    data-target='.modal-set-gagal'
                                    data-toggle='tooltip' data-placement='top' title='Set Gagal'
                                >
                                <i class='fa fa-remove'></i>
                                </a>";
                        }
                    }
                    return $button;

                });
            }

        $Datatables = $Datatables
            ->escapeColumns(['*'])
            ->make(true);

        return $Datatables;
    }

    public function getLogTransactionList(Request $request){    
        $index = DB::table('api_logging_outbound')
                ->select('type', 'body', 'create_datetime as log_time')
                ->where('pos_id', $request->pos_id)
                ->orderBy('create_datetime', 'desc')
                ->get();

        $Datatables = Datatables::of($index)
            ->editColumn('log_time', function($getData){
                return date('Y-m-d H:i:s', strtotime($getData->log_time));
            })
            ->escapeColumns(['*'])
            ->make(true);
        return $Datatables;
    }

    public function setGagal(Request $request){

        $message = [
            'remark_user.required'      => 'This field required',
            'remark_internal.required'  => 'This field required',
            'password.required'         => 'This field required'
        ];

        $validator = Validator::make($request->all(), [
            'remark_user'       => 'required',
            'remark_internal'   => 'required',
            'password'          => 'required'
        ], $message);

        if($validator->fails())
        {
        	return response()->json($validator->messages(), 422);
            /*return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('set-gagal-false', 'Something Errors');*/
        }        

        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
            	'status' => 'FAIL',
            	'errorKey' => 'wrong.password'
            ]);
            //return redirect()->back()->with('gagal', 'wrong.password');
        }

        $transaction = Pos::join('sw_agent as a', 'a.agent_id', 'sw_pos.agent_id')
                            ->join('sw_product as b', 'b.product_id', 'sw_pos.product_id')
                            ->select('a.agent_name', 'b.product_code', 'sw_pos.receiver_phone_number', 'sw_pos.status', 'sw_pos.update_datetime')
                            ->where('sw_pos.pos_id', $request->pos_id)
                            ->first();

        if ($transaction->status == 'S') {
            $now = new \DateTime(date('Y-m-d H:i:s', time()));            
            $updateDateTime = new \DateTime(date('Y-m-d H:i:s', strtotime($transaction->update_datetime)));
            //rentang hari
            $diff = $now->diff($updateDateTime)->days;            
                
            if ($diff > 3) {
                //return redirect()->back()->with('gagal', 'more.than.3.days');
            	return response()->json([
	            	'status' => 'FAIL',
	            	'errorKey' => 'more.than.3.days'
	            ]);
            }
        } 

        try {
            ini_set('max_execution_time', 300);
            $client = new \GuzzleHttp\Client([
                            'headers' => [
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ],
                          ]);

            $res = $client->request('POST',env('CONSUMER_HOST','http://localhost:9020').'/manual/fail', [
                            'json' => [
                              'posId' => $request->pos_id,
                              'internalRemark'  => $request->remark_internal,
                              'statusRemark'  => $request->remark_user,
                              'userId'  => Auth::id()
                            ]
                          ])->getbody();

            $result = json_decode($res);

            if ($result->status == 'OK') {                

                $transaction->internalRemark = $request->remark_internal;
                $transaction->statusRemark = $request->remark_user;
                $transaction->status = 'GAGAL';
                
                Mail::send('email.perubahanStatusTransaksi', ['data' => $transaction], function($message) use ($transaction) {
                    $recipients = explode(',', env('TRX_CHANGE_STATUS_MAIL_RECIPIENT')); 
                    $message->from(env('MAIL_FROM'), 'Paloma Shopway')
                            ->to($recipients)
                            ->subject('Perubahan Status Transaksi');
                });

                return response()->json([
		        	'status' => 'OK'		        	
		        ]);
            }else{                
                return response()->json([
		        	'status' => 'FAIL',
		        	'errorKey' => $result->errorKey
		        ]);
            }  

      } catch (\Exception $e) {     
            //return redirect()->back()->with('gagal', $response);
            return response()->json([
            	'status' => 'FAIL',
            	'errorKey' => 'error.server'
            ]);
      }
      
      /*return redirect()->back()
                ->with($status, $response)
                ->with('alert', $alert);*/
    }

    public function setSukses(Request $request){
        $message = [
            'remark_user.required'      => 'This field required',
            'remark_internal.required'  => 'This field required',
            'password.required'         => 'This field required'
        ];

        $validator = Validator::make($request->all(), [
            'remark_user'       => 'required',
            'remark_internal'   => 'required',
            'password'          => 'required'
        ], $message);

        if($validator->fails())
        {
            return response()->json($validator->messages(), 422);
        }

        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
            	'status' => 'FAIL',
            	'errorKey' => 'wrong.password'
            ]);
        }

        try {
            ini_set('max_execution_time', 300);
            Log::debug(">>>>>1");
            $client = new \GuzzleHttp\Client([
                            'headers' => [
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ],
                          ]);
            Log::debug(">>>>>2");
            $res = $client->request('POST',env('CONSUMER_HOST','http://localhost:9021').'/manual/success', [
                            'json' => [
                              'posId' => $request->pos_id,
                              'internalRemark'  => $request->remark_internal,
                              'statusRemark'  => $request->remark_user,
                              'userId'  => Auth::id()
                            ]
                          ])->getbody();
            Log::debug(">>>>>3");
            Log::debug(">>>>>$res");
            $result = json_decode($res);

            if ($result->status == 'OK') {
                Log::debug(">>>>>4");
                $transaction = Pos::join('sw_agent as a', 'a.agent_id', 'sw_pos.agent_id')
                                    ->join('sw_product as b', 'b.product_id', 'sw_pos.product_id')
                                    ->select('a.agent_name', 'b.product_code', 'sw_pos.receiver_phone_number')
                                    ->where('sw_pos.pos_id', $request->pos_id)
                                    ->first(); 

                $transaction->internalRemark = $request->remark_internal;
                $transaction->statusRemark = $request->remark_user;
                $transaction->status = 'SUKSES';
                
                Mail::send('email.perubahanStatusTransaksi', ['data' => $transaction], function($message) use ($transaction) {
                    $recipients = explode(',', env('TRX_CHANGE_STATUS_MAIL_RECIPIENT'));
                    $message->from(env('MAIL_FROM'), 'Paloma Shopway')
                            ->to($recipients)
                            ->subject('Perubahan Status Transaksi');
                });
                
                return response()->json([
		        	'status' => 'OK'		        	
		        ]);
            }else{
                Log::debug(">>>>>5");
                return response()->json([
		        	'status' => 'FAIL',
		        	'errorKey' => $result->errorKey
		        ]);
            }  

      } catch (\Exception $e) {
            Log::debug(">>>>>6");
            return response()->json([
            	'status' => 'FAIL',
            	'errorKey' => 'error.server'
            ]);
      }

      /*return redirect()->back()
                ->with($status, $response)
                ->with('alert', $alert);*/
    }

    public function getDetailChangeStatusTransaction(Request $request){        

        $index = DB::table('sw_pos_status_log as a')
                    ->join('wa_users as b', 'b.id', 'a.user_id')                    
                    ->select('a.*', 'b.name')
                    ->where('a.pos_id', $request->pos_id)
                    ->get();           

        $Datatables = Datatables::of($index)
            ->editColumn('update_status_datetime', function($getData){
                return date('Y-m-d H:i:s', strtotime($getData->update_status_datetime));
            })
            ->escapeColumns(['*'])
            ->make(true);

        return $Datatables;

    }
}
