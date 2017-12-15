<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\SalesDepositTransaction;
use App\Models\SalesDepositTransactionDetail;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;

class SalesDepositController extends Controller
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

    function index(Request $request){

      $message = [      
      'f_start_date.date'     => 'Invalid filter',
      'f_end_date.date'       => 'Invalid filter'
    ];

    $validator = Validator::make($request->all(), [        
        'f_start_date'  => 'date|nullable',
        'f_end_date'    => 'date|nullable'
      ], $message);

    if($validator->fails())
    {
      return redirect()->route('sales-deposit-transaction.index');
    }

    	return view('sales-deposit-transaction.index', compact('request'));
    }

    function getSalesDepositTransactionList(Request $request){            
        $getDatas = DB::table('sw_sales_deposit_transaction as a')
                    ->join('sw_sales as b', 'b.sales_id', 'a.sales_id')
                    ->join('wa_users as c', 'c.id', 'b.user_id')
                    ->join('sw_sales_deposit_transaction_detail as d', 'd.sales_deposit_transaction_id', 'a.sales_deposit_transaction_id')
                    ->join('sw_agent as e', 'e.agent_id', 'd.agent_id')
                    ->select([
                            'a.sales_deposit_transaction_id',
                            'c.name as sales_name',
                            'agent_name',
                            'doc_no',
                            'doc_date',
                            'total_amount_deposit',
                            'a.status as status',
                            'a.version as version'
                        ]);

          if(isset($request->f_sales_name) && $request->f_sales_name != ''){
              $getDatas->where(DB::raw('lower(c.name)'), 'like', '%'.strtolower($request->f_sales_name).'%');
          }

          if(isset($request->f_status_setor) && $request->f_status_setor != ''){
            $getDatas->where('a.status', $request->f_status_setor);
          }

          if(isset($request->f_start_date) && $request->f_start_date != '' && isset($request->f_end_date) && $request->f_end_date != ''){

            $f_start_date = date('Ymd', strtotime($request->f_start_date));
            $f_end_date = date('Ymd', strtotime($request->f_end_date));

            $getDatas->whereBetween('a.doc_date', [$f_start_date, $f_end_date]);
          }
          
          $getDatas->orderBy('a.doc_date', 'desc')                    
                    ->get();                

                $start      = 1;
          $Datatables = Datatables::of($getDatas)
              ->addColumn('slno', function ($getData) use (&$start) {
                      return $start++;
                  })
              ->editColumn('doc_date', function ($getData) {
                      return date('Y-m-d', strtotime($getData->doc_date));
                  })
              ->editColumn('total_amount_deposit', function ($getData) {
                      return 'Rp. ' . number_format($getData->total_amount_deposit, 2);
                  })
              ->editColumn('status', function ($getData) {                        
                  if ($getData->status == 'D') {
                          return "
                              <span 
                                  class='label label-danger' 
                                  data-toggle='tooltip' 
                                  data-placement='top' 
                                  title='Belum Disetorkan'
                                >Belum Disetorkan</span><br>";
                      
                      }else if ($getData->status == 'R') {
                          return "
                              <span 
                                  class='label label-success' 
                                  data-toggle='tooltip' 
                                  data-placement='top' 
                                  title='Sudah Disetorkan'
                                >Sudah Disetorkan</span><br>";
                      }
              })
              ->addColumn('action', function ($getData) {
                  $actionHtml = '';
                  /*if (Auth::user()->can('read-sales-deposit-transaction')) {
                      $actionHtml .= "
                          <a href='" . route('salesDepositTransaction.detail', ['id' => $getData->sales_deposit_transaction_id]) ."'' 
                          class='btn btn-xs btn-info btn-sm' 
                          data-toggle='tooltip' 
                          data-placement='top' 
                          title='Detail'>
                          <i class='fa fa-archive'></i></a>";
                  }*/

                  if (Auth::user()->can('set-sudah-setor-sales-deposit-transaction')) {                      
                    if ($getData->status == 'D') {
                            $actionHtml .= "
                                <a 
                                  href='' 
                                  class='setor btn btn-xs btn-success btn-sm' 
                                  data-value='".$getData->sales_deposit_transaction_id."' 
                                  data-version='".$getData->version."' 
                                  data-toggle='modal' 
                                  data-target='.modal-set-setor'
                                  data-toggle='tooltip' 
                                  data-placement='top' 
                                  title='Set Sudah Setor'
                                >
                                  <i class='fa fa-dollar'></i>
                                </a><br>";                        
                        }                      
                  }

                  if (Auth::user()->can('set-belum-setor-sales-deposit-transaction')) {
                                      
                    if ($getData->status == 'R') {
                            $actionHtml .= "
                                <a 
                                  href='' 
                                  class='belum-setor btn btn-xs btn-danger btn-sm' 
                                  data-value='".$getData->sales_deposit_transaction_id."' 
                                  data-version='".$getData->version."' 
                                  data-toggle='modal' 
                                  data-target='.modal-set-belum-setor'
                                  data-toggle='tooltip' 
                                  data-placement='top' 
                                  title='Set Belum Setor'
                                >
                                  <i class='fa fa-dollar'></i>
                                </a><br>";                        
                        }                      
                  }
        
                  return $actionHtml;
              });

          $Datatables = $Datatables
              ->escapeColumns(['*'])
              ->make(true);

          return $Datatables;
    }

    function setSudahSetor($id, $version){
        $call = SalesDepositTransaction::find($id);

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
            DB::transaction(function () use($call) {
              $call->increment('version');
              $call->status         = 'R';
              $call->update_user_id = Auth::user()->id;
              $call->update_datetime= Carbon::now()->format('YmdHis');
              $call->update();
            });
          }
          return redirect()->back()
            ->with('alert', $alert)
            ->with('berhasil', $info);
    }

    function setBelumSetor($id, $version){
        $call = SalesDepositTransaction::find($id);

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
            DB::transaction(function () use($call) {
              $call->increment('version');
              $call->status         = 'D';
              $call->update_user_id = Auth::user()->id;
              $call->update_datetime= Carbon::now()->format('YmdHis');
              $call->update();
            });
          }
          return redirect()->back()
            ->with('alert', $alert)
            ->with('berhasil', $info);
    }

    /*function detail($salesDepositTranscationId){      
      $salesDepositTransaction = SalesDepositTransaction::join('sw_sales', 'sw_sales.sales_id', 'sw_sales_deposit_transaction.sales_id')
                    ->join('wa_users', 'wa_users.id', 'sw_sales.user_id')
                    ->select([
                            'sales_deposit_transaction_id',
                            'name',
                            'doc_no',
                            'doc_date',
                            'total_amount_deposit',
                            'sw_sales_deposit_transaction.status as status',
                    ])
                    ->where('sw_sales_deposit_transaction.sales_deposit_transaction_id', $salesDepositTranscationId)                    
                    ->first();   

      return view('sales-deposit-transaction.detail')
        ->with('salesDepositTransaction', $salesDepositTransaction);
    }*/

    /*function getSalesDepositTransactionDetailList($salesDepositTranscationId){
      $getDatas = SalesDepositTransactionDetail::join('sw_agent', 'sw_agent.agent_id', 'sw_sales_deposit_transaction_detail.agent_id')                    
                    ->select([
                            'sales_deposit_transaction_detail_id',                            
                            'agent_name',
                            'sw_sales_deposit_transaction_detail.phone_number as phone_number',                            
                            'amount_deposit'
                        ])
                    ->where('sw_sales_deposit_transaction_detail.sales_deposit_transaction_id', $salesDepositTranscationId)
                    ->orderBy('line_no', 'asc')
                    ->get();                

                $start      = 1;
          $Datatables = Datatables::of($getDatas)
              ->addColumn('slno', function ($getData) use (&$start) {
                      return $start++;
                  })
              ->editColumn('amount_deposit', function ($getData) {
                      return 'Rp. ' . number_format($getData->amount_deposit, 2);
                  }); 

          $Datatables = $Datatables
              ->escapeColumns(['*'])
              ->make(true);

          return $Datatables;
    }*/

}
