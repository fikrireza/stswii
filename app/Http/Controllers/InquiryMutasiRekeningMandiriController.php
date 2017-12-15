<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Yajra\Datatables\Facades\Datatables;
use Validator;
use Auth;
use Carbon\Carbon;
use Mail;

class InquiryMutasiRekeningMandiriController extends Controller
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
            'f_date.date_format'  => 'Invalid filter'            
        ];

        $validator = Validator::make($request->all(), [                
            'f_date'    => 'date_format:d/m/Y|nullable'
        ], $message);

        if($validator->fails())
        {
            return redirect()->route('inquiry-mutasi-rekening-mandiri.index');
        }

        return view('inquiry-mutasi-rekening-mandiri.index', compact('request'));
    }

    public function getMutasiRekeningMandiriList(Request $request){
        
        $index = DB::table('sw_mandiri_trx_log as a')
                ->leftJoin('wl_unique_codes as b', 'b.unique_code_id', '=', 'a.unique_code_id')
                ->leftJoin('sw_agent as c', 'c.client_id', '=', DB::raw('cast(b.client_id as varchar)'))                
                ->select('a.tanggal_waktu', 'a.deskripsi', 'a.debit', 'a.kredit', 'a.saldo', 'b.unique_code_date', 'b.unique_code', 'c.agent_name', 'c.paloma_member_code');
                

        if(isset($request->f_date) && $request->f_date != ''){
            $index->where('a.value_date', '=', $request->f_date);
        }

        $index->orderBy('a.sw_mandiri_trx_log_id')->get();

        
        
        $start      = 1;
        $Datatables = Datatables::of($index)
            ->addColumn('slno', function ($getData) use (&$start) {
                return $start++;
            })                        
            ->editColumn('debit', function($getData){
                return 'Rp. ' . $getData->debit;
            })
            ->editColumn('kredit', function($getData){
                return 'Rp. ' . $getData->kredit;
            })
            ->editColumn('saldo', function($getData){
                return 'Rp. ' . ($getData->saldo == '' ? 0 : $getData->saldo);
            })
            ->editColumn('unique_code_date', function($getData){
                return $getData->unique_code_date == '' ? '' : date('Y-m-d', strtotime($getData->unique_code_date));
            });
            

        $Datatables = $Datatables
            ->escapeColumns(['*'])
            ->make(true);

        return $Datatables;
    }

}
