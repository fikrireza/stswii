<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use DB;
use Yajra\Datatables\Facades\Datatables;
use Validator;

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
				->select('sw_agent.agent_name', 'sw_agent.phone_number', 'sw_pos.purchase_datetime', 'sw_pos.gross_sell_price', 'sw_pos.receiver_phone_number', 'sw_pos.status', 'sw_pos.status_remark', 'sw_partner_product.partner_product_code', 'sw_product.product_code', 'sw_partner_pulsa.partner_pulsa_code');
				

		if(isset($request->f_agent_name) && $request->f_agent_name != ''){
			$index->where(DB::raw('lower(sw_agent.agent_name)'), 'like', '%'.strtolower($request->f_agent_name).'%');
		}

		if(isset($request->f_agent_phone) && $request->f_agent_phone != ''){
			$index->where('sw_agent.phone_number', $request->f_agent_phone); 
		}

		if(isset($request->f_transaction_status) && $request->f_transaction_status != ''){
			$index->where('sw_pos.status', $request->f_transaction_status);
		}

		if(isset($request->f_start_date) && $request->f_start_date != '' && isset($request->f_end_date) && $request->f_end_date != ''){

			$f_start_date = date('YmdHis', strtotime($request->f_start_date.' 00:00:00'));
			$f_end_date = date('YmdHis', strtotime($request->f_end_date.' 23:59:59'));
			$index->whereBetween('sw_pos.purchase_datetime', [$f_start_date, $f_end_date]);
		}

		$index->orderBy('sw_pos.purchase_datetime', 'ASC')->get();
		
		$start      = 1;
        $Datatables = Datatables::of($index)
        	->addColumn('slno', function ($getData) use (&$start) {
                return $start++;
            })
             ->editColumn('purchase_datetime', function ($getData) {
                return date('Y-m-d H:i:s', strtotime($getData->purchase_datetime));
            })
            ->editColumn('gross_sell_price', function($getData){
            	return 'Rp. ' . number_format($getData->gross_sell_price, 2);
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
            	$actionHtml = $actionHtml."<a href='' class='remark' 
            	data-agent-name='".$getData->agent_name."'
            	data-agent-phone='".$getData->phone_number."'
            	data-transaction-date='".date('Y-m-d H:i:s', strtotime($getData->purchase_datetime))."'
            	data-ordered-product-code='".$getData->product_code."'
            	data-product-price='".number_format($getData->gross_sell_price, 2)."'
            	data-destination-phone='".$getData->receiver_phone_number."'
            	data-partner-product-code='".$getData->partner_product_code."'
            	data-partner-code='".$getData->partner_pulsa_code."'
            	data-transaction-status='".$transactionStatus."'
            	data-status-remark='".$getData->status_remark."' data-toggle='modal' data-target='.modal-detail'><span class='btn btn-info btn-xs btn-sm' data-toggle='tooltip' data-placement='top' title='Detail Transaction'><i class='fa fa-archive'></i></span></a>";
            	return $actionHtml;
            });

		$Datatables = $Datatables
            ->escapeColumns(['*'])
            ->make(true);

        return $Datatables;
	}
}
