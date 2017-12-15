<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pos;
use App\Models\Agent;
use App\Models\SalesDepositTransaction;
use App\Models\BalanceLog;
use Session;
use DB;
use Excel;

class ReportController extends Controller
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

    public function bySupplierPkp()
    {
      return view('report.supplier-pkp');
    }

    public function postBySupplierPkp(Request $request)
    {

        $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

        $getData = Pos::leftjoin('sw_product', 'sw_product.product_id', '=', 'sw_pos.product_id')
                        ->join('sw_agent', 'sw_agent.agent_id', '=', 'sw_pos.agent_id')
                        ->leftjoin('sw_partner_product', 'sw_partner_product.partner_product_id', '=', 'sw_pos.partner_product_id')
                        ->leftjoin('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', '=', 'sw_partner_product.partner_pulsa_id')
                        ->select('sw_agent.agent_name', 'sw_partner_pulsa.partner_pulsa_code','sw_partner_product.partner_product_code', 'sw_product.product_code', 
                          DB::raw('to_date(purchase_datetime, \'YYYYMMDDHH24MISS\') AS tanggal'),                           
                          'sw_pos.gross_purch_price as amount_buy',
                          'sw_pos.gross_sell_price as amount_sell',
                          DB::raw('(sw_pos.gross_sell_price - sw_pos.gross_purch_price) as profit'))
                        ->where('sw_pos.purchase_datetime', 'like', $tahun_bulan.'%')
                        ->where('sw_pos.status', 'S')
                        ->where('sw_partner_pulsa.flg_pkp', 'Y')
                        
                        ->get()
                        ->toArray();        

        if(!$getData){
          return redirect()->route('report.bySupplierPkp')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Sales By Supplier PKP - '.$request->tahun_bulan, function($excel) use($getData, $request){
          $excel->sheet('Sales By Supplier PKP', function($sheet) use ($getData, $request)
          {
            $sheet->row(1, array('Bulan'));
            $sheet->row(2, array($request->tahun_bulan));
            $sheet->fromArray($getData, null, 'A4', true);
          });
        })->download('csv');

        return redirect()->route('report.bySupplierPkp');
    }

    public function bySupplierNonPkp()
    {
      return view('report.supplier-non-pkp');
    }

    public function postBySupplierNonPkp(Request $request)
    {

        $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

        $getData = Pos::leftjoin('sw_product', 'sw_product.product_id', '=', 'sw_pos.product_id')
                        ->join('sw_agent', 'sw_agent.agent_id', '=', 'sw_pos.agent_id')
                        ->leftjoin('sw_partner_product', 'sw_partner_product.partner_product_id', '=', 'sw_pos.partner_product_id')
                        ->leftjoin('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', '=', 'sw_partner_product.partner_pulsa_id')
                        ->select('sw_agent.agent_name', 'sw_partner_pulsa.partner_pulsa_code','sw_partner_product.partner_product_code', 'sw_product.product_code', 
                          DB::raw('to_date(purchase_datetime, \'YYYYMMDDHH24MISS\') AS tanggal'),
                          'sw_pos.gross_purch_price as amount_buy',
                          'sw_pos.gross_sell_price as amount_sell',
                          DB::raw('(sw_pos.gross_sell_price - sw_pos.gross_purch_price) as profit'))
                        ->where('sw_pos.purchase_datetime', 'like', $tahun_bulan.'%')
                        ->where('sw_pos.status', 'S')
                        ->where('sw_partner_pulsa.flg_pkp', 'N')
                        ->get()
                        ->toArray();

        if(!$getData){
          return redirect()->route('report.bySupplierNonPkp')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Sales By Supplier Non PKP - '.$request->tahun_bulan, function($excel) use($getData, $request){
          $excel->sheet('Sales By Supplier Non PKP', function($sheet) use ($getData, $request)
          {
            $sheet->row(1, array('Bulan'));
            $sheet->row(2, array($request->tahun_bulan));
            $sheet->fromArray($getData, null, 'A4', true);
          });
        })->download('csv');

        return redirect()->route('report.bySupplierNonPkp');
    }


    public function byAgent()
    {
        return view('report.agent');
    }

    public function postByAgent(Request $request)
    {
        $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

        $getData = Pos::leftjoin('sw_product', 'sw_product.product_id', '=', 'sw_pos.product_id')
                        ->leftjoin('sw_agent', 'sw_agent.agent_id', '=', 'sw_pos.agent_id')
                        ->join('sw_provider', 'sw_provider.provider_id', '=', 'sw_product.provider_id')
                        ->select('sw_agent.agent_name', 'sw_agent.paloma_member_code', 'sw_agent.phone_number', 'sw_product.product_code', 'sw_provider.provider_code', DB::raw('COUNT(sw_pos.pos_id) as qty'), DB::raw('SUM(sw_pos.gross_sell_price) as amount'))
                        ->where('sw_pos.purchase_datetime', 'like', $tahun_bulan.'%')
                        ->where('sw_pos.status', 'S')
                        ->groupBy(['sw_agent.agent_name', 'sw_agent.paloma_member_code', 'sw_agent.phone_number','sw_product.product_code', 'sw_provider.provider_code'])
                        ->orderBy('sw_agent.agent_name')
                        ->orderBy('sw_product.product_code')
                        ->orderBy('sw_provider.provider_code')
                        ->get()
                        ->toArray();

        if(!$getData){
          return redirect()->route('report.byAgent')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Sales By Agent - '.$request->tahun_bulan, function($excel) use($getData, $request){
          $excel->sheet('Sales By Agent', function($sheet) use ($getData, $request)
          {
            $sheet->row(1, array('Bulan'));
            $sheet->row(2, array($request->tahun_bulan));
            $sheet->fromArray($getData, null, 'A4', true);
          });
        })->download('csv');

        return redirect()->route('report.byAgent');
    }


    public function byProvider()
    {
      return view('report.provider');
    }

    public function postByProvider(Request $request)
    {
      $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

      $getData = Pos::leftjoin('sw_product', 'sw_product.product_id', '=', 'sw_pos.product_id')
                      ->join('sw_provider', 'sw_provider.provider_id', '=', 'sw_product.provider_id')
                      ->select('sw_product.product_code', 'sw_provider.provider_code', DB::raw('COUNT(sw_pos.pos_id) as qty'), DB::raw('SUM(sw_pos.gross_sell_price) as amount_sell'), DB::raw('SUM(sw_pos.gross_purch_price) as amount_buy'), DB::raw('SUM(sw_pos.gross_sell_price - sw_pos.gross_purch_price) as profit'))
                      ->where('sw_pos.purchase_datetime', 'like', $tahun_bulan.'%')
                      ->where('sw_pos.status', 'S')
                      ->groupBy(['sw_product.product_code', 'sw_provider.provider_code'])
                      ->orderBy('sw_product.product_code')
                      ->orderBy('sw_provider.provider_code')
                      ->get()
                      ->toArray();

      if(!$getData){
        return redirect()->route('report.byProvider')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Sales By Provider - '.$request->tahun_bulan, function($excel) use($getData, $request){
        $excel->sheet('Sales By Provider', function($sheet) use ($getData, $request)
        {
          $sheet->row(1, array('Bulan'));
          $sheet->row(2, array($request->tahun_bulan));
          $sheet->fromArray($getData, null, 'A4', true);
        });
      })->download('csv');

      return redirect()->route('report.byProvider');
    }


    public function byTopUpDepositPartner()
    {
        return view('report.depositPartner');
    }

    public function postByTopUpDepositPartner()
    {

        $getData = [];

        if(!$getData){
          return redirect()->route('report.byTopUpDepositPartner')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Topup Deposit Partner', function($excel) use($getData){
          $excel->sheet('Report Topup Deposit Partner', function($sheet) use ($getData)
          {
            $sheet->fromArray($getData, null, 'A1', true);
          });
        })->download('csv');

        return redirect()->route('report.byTopUpDepositPartner');
    }

    public function byDepositHarianAgent()
    {
        return view('report.depositHarianAgent');
    }

    public function postByDepositHarianAgent(Request $request)
    {
        $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

        $getData = BalanceLog::join('wl_balance as c', 'wl_balance_log.balance_id', '=', 'c.balance_id')
                    ->join('sw_agent as d', 'd.client_id', '=', DB::raw('cast(c.client_id as varchar)'))
                    ->select('d.agent_id', 'd.agent_name', 'd.agent_name', 'd.phone_number', 'wl_balance_log.amount', DB::raw('TO_DATE(wl_balance_log.ref_datetime,\'YYYYMMDDHH24MISS\') AS tanggal'), DB::raw(' case when d.paloma_member_code = \'\' then \'Tradisional\' else  \'MLM\' end as channel'))
                    ->where('wl_balance_log.ref_doc_type_id', '=', -99)
                    ->whereNotExists(function($query){
                        $query->select(DB::raw(1))
                              ->from('wl_balance_log as b')
                              ->whereRaw('wl_balance_log.ref_doc_no = b.ref_doc_no AND b.flag_reversal = \'Y\' ');
                    })
                    ->where(DB::raw('LEFT(wl_balance_log.ref_datetime,6)'), '=', $tahun_bulan)
                    //->groupBy('agent_id', 'd.agent_name', 'd.phone_number', DB::raw('LEFT(wl_balance_log.ref_datetime,6)'))
                    ->orderBy('d.agent_name')
                    ->orderBy('wl_balance_log.ref_datetime')
                    ->get();        

        if(!$getData){
          return redirect()->route('report.byDepositHarianAgent')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Deposit Harian Agent - '.$request->tahun_bulan, function($excel) use($getData, $request){
          $excel->sheet('Report Deposit Harian Agent', function($sheet) use ($getData, $request)
          {
            $sheet->row(1, array('Bulan'));
            $sheet->row(2, array($request->tahun_bulan));
            $sheet->fromArray($getData, null, 'A4', true);
          });
        })->download('csv');

        return redirect()->route('report.byDepositHarianAgent');
    }

    public function byRekapSalesHarianAgent()
    {
        return view('report.rekapSalesHarianAgent');
    }

    public function postByRekapSalesHarianAgent(Request $request)
    {
      $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

      $getData = Pos::join('sw_agent', 'sw_agent.agent_id', '=', 'sw_pos.agent_id')
                      ->select('sw_agent.agent_name', 'sw_agent.paloma_member_code', 'sw_agent.phone_number', DB::raw('to_date(sw_pos.purchase_datetime, \'YYYYMMDDHH24MISS\') as tanggal'), DB::raw('SUM(sw_pos.gross_sell_price) as jumlah_amount'), DB::raw('COUNT(*) as jumlah_trx'))
                      ->where('sw_pos.purchase_datetime', 'like', $tahun_bulan.'%')
                      ->where('sw_pos.status', 'S')
                      ->groupBy(['sw_agent.agent_name', 'sw_agent.paloma_member_code', 'sw_agent.phone_number', 'tanggal'])
                      ->orderBy('sw_agent.agent_name')
                      ->orderBy('tanggal')
                      ->get()
                      ->toArray();

      if(!$getData){
        return redirect()->route('report.byRekapSalesHarianAgent')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Rekap Sales Harian Agent - '.$request->tahun_bulan, function($excel) use($getData, $request){
        $excel->sheet('Rekap Sales Harian Agent', function($sheet) use ($getData, $request)
        {
          $sheet->row(1, array('Bulan'));
          $sheet->row(2, array($request->tahun_bulan));
          $sheet->fromArray($getData, null, 'A4', true);
        });
      })->download('csv');

      return redirect()->route('report.byRekapSalesHarianAgent');
    }

    public function byWeeklySalesSummary()
    {
        return view('report.weeklySalesSummary');
    }

    public function postByWeeklySalesSummary(Request $request)
    {
      $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

      $session_id = Session::getId();

        $pdo = \DB::connection()->getPdo();
        $pdo->beginTransaction();
            
        $stmt = $pdo->prepare("SELECT * FROM r_weekly_sales_summary('$session_id', '$tahun_bulan')");
                      
        $stmt->execute();
        $cursors = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $stmt->closeCursor();
            
        // get each result set
        $results = [];
        $getData = [];
        foreach($cursors as $k=>$v){
            $stmt = $pdo->query('FETCH ALL IN "'. $v->r_weekly_sales_summary.'";');
            $results[$v->r_weekly_sales_summary] =  $stmt->fetchAll(\PDO::FETCH_OBJ);
            $stmt->closeCursor();
        }
        $pdo->commit();
        unset($stmt);


      foreach ($results['refDetail'] as $value) {
        $getData[] = get_object_vars($value);
      }

      if($getData == null){
        return redirect()->route('report.byWeeklySalesSummary')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Weekly Sales Summary- '.$request->tahun_bulan, function($excel) use($getData, $request){
        $excel->sheet('Weekly Sales Summary', function($sheet) use ($getData, $request)
        {
          $sheet->row(1, array('Bulan'));
          $sheet->row(2, array($request->tahun_bulan));
          $sheet->fromArray($getData, null, 'A4', true);
        });
      })->download('csv');

      return redirect()->route('report.byWeeklySalesSummary');
    }

    public function bySaldoDepositAgent()
    {
        return view('report.saldoDepositAgent');
    }

    public function postBySaldoDepositAgent(Request $request)
    {      

      try {
        
      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', env('WALLET_HOST','http://localhost:9020').'/report/agentBalance')
          ->getbody();

          $result = json_decode($res);

          if ($result->status == 'OK') {
            $payload = $result->payload->agentList;
            $getData = [];

                foreach ($payload as $value) {
                  $getData[] = get_object_vars($value);
                }
        
              if($getData == null){
                return redirect()->route('report.bySaldoDepositAgent')->with('gagal', 'Data Not Found')->withInput();
              }

              return Excel::create('Report Saldo Deposit Agent', function($excel) use($getData){
                $excel->sheet('Saldo Deposit Agent', function($sheet) use ($getData)
                {
                  $sheet->fromArray($getData, null, 'A1', true);
                });
              })->download('csv');

              return redirect()->route('report.bySaldoDepositAgent');
          }else{
            return redirect()->route('report.bySaldoDepositAgent')->with('gagal', 'Maaf, terjadi kesalahan server')->withInput();
          }
      } catch (\Exception $e) {
          $response = 'Status Server '.$e->getResponse()->getStatusCode();
          return redirect()->route('report.bySaldoDepositAgent')->with('gagal', $response);
      }
    }

    public function bySalesDeposit()
    {
      return view('report.sales-deposit');
    }

    public function postBySalesDeposit(Request $request)
    {
      $start_date = date('Ymd', strtotime($request->start_date));
      $end_date = date('Ymd', strtotime($request->end_date));

      $getData = SalesDepositTransaction::join('sw_sales as b', 'b.sales_id', 'sw_sales_deposit_transaction.sales_id')
                    ->join('wa_users as c', 'c.id', 'b.user_id')
                    ->join('sw_sales_deposit_transaction_detail as d', 'd.sales_deposit_transaction_id', 'sw_sales_deposit_transaction.sales_deposit_transaction_id')
                    ->join('sw_agent as e', 'e.agent_id', 'd.agent_id')
                    ->whereBetween('sw_sales_deposit_transaction.doc_date', [$start_date, $end_date])
                    ->select(                            
                            'c.name as sales_name',
                            'doc_date as tanggal_deposit',
                            'total_amount_deposit as nilai deposit',
                            'agent_name'                                       
                        )
                    ->orderBy('sw_sales_deposit_transaction.doc_date', 'desc')                    
                    ->get()
                    ->toArray();      

      if(!$getData){
        return redirect()->route('report.bySalesDeposit')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Sales Deposit - '.$request->start_date.' - '.$request->end_date, function($excel) use($getData, $request){
        $excel->sheet('Sales Deposit', function($sheet) use ($getData, $request)
        {
          $sheet->row(1, array('Tanggal Mulai', 'Tanggal Berakhir'));
          $sheet->row(2, array($request->start_date, $request->end_date));
          $sheet->fromArray($getData, null, 'A4', true);
        });
      })->download('csv');

      return redirect()->route('report.bySalesDeposit');
    }

     public function byAgentMemberPaloma()
    {
      return view('report.agentMemberPaloma');
    }

    public function postByAgentMemberPaloma(Request $request)
    {

      $getData = Agent::join('wl_balance as b', DB::raw('cast(b.client_id as varchar)'), 'sw_agent.client_id')            ->where('sw_agent.paloma_member_code', '!=', '')
                    ->select(                            
                            'agent_name',
                            'paloma_member_code',
                            'phone_number',
                            DB::raw('to_date(sw_agent.create_datetime, \'YYYYMMDDHH24MISS\') as join_date'),
                            'balance_amount as nilai_deposit'                                       
                        )
                    ->orderBy('sw_agent.agent_name')                    
                    ->get()
                    ->toArray();      

      if(!$getData){
        return redirect()->route('report.byAgentMemberPaloma')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Agent Member Paloma', function($excel) use($getData){
        $excel->sheet('Agent Member Paloma', function($sheet) use ($getData)
        {
          $sheet->fromArray($getData, null, 'A1', true);
        });
      })->download('csv');

      return redirect()->route('report.byAgentMemberPaloma');
    }

	public function byAgentMlm()
    {
      return view('report.agentMlm');
    }

    public function postByAgentMlm(Request $request)
    {
      $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

      $getData = Pos::leftJoin('sw_agent as b', 'b.agent_id', 'sw_pos.agent_id')                                        
                    ->where('b.paloma_member_code', '!=', '')
                    ->where('sw_pos.status', '=', 'S')
                    ->where('sw_pos.purchase_datetime', 'like', $tahun_bulan.'%')
                    ->select(                            
                            'agent_name',
                            'paloma_member_code',
                            DB::raw('count(*) as jumlah_transaksi'),
                            DB::raw('sum(gross_sell_price) as nilai_trx')                                       
                        )                    
                    ->groupBy(['agent_name', 'paloma_member_code'])
                    ->orderBy('agent_name')                    
                    ->get()
                    ->toArray();      

      if(!$getData){
        return redirect()->route('report.byAgentMlm')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Sales By Agent MLM', function($excel) use($getData, $request){
        $excel->sheet('Sales By Agent MLM', function($sheet) use ($getData, $request)
        {
          $sheet->row(1, array('Bulan'));
          $sheet->row(2, array($request->tahun_bulan));
          $sheet->fromArray($getData, null, 'A4', true);
        });
      })->download('csv');

      return redirect()->route('report.byAgentMlm');
    }

    public function byDataAgentNotActive()
    {
      return view('report.dataAgentNotActive');
    }

    public function postByDataAgentNotActive(Request $request)
    {

      $getData = Agent::join('wl_balance as b', DB::raw('cast(b.client_id as varchar)'), 'sw_agent.client_id')
                    ->select(                            
                            'agent_name',
                            'paloma_member_code',
                            'phone_number'
                        )
                    ->whereNotExists(function($query){
                        $query->select(DB::raw(1))
                          ->from('wl_balance_log as c')
                          ->whereRaw('c.balance_id = b.balance_id');                          
                    })                                        
                    ->orderBy('agent_name')                    
                    ->get()
                    ->toArray();      

      if(!$getData){
        return redirect()->route('report.dataAgentNotActive')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Data Agent Not Active', function($excel) use($getData){
        $excel->sheet('Data Agent Not Active', function($sheet) use ($getData)
        {
          $sheet->fromArray($getData, null, 'A1', true);
        });
      })->download('csv');

      return redirect()->route('report.dataAgentNotActive');
    }

    public function byStatistikTransaksiError()
    {
      return view('report.statistikTransaksiError');
    }

    public function postByStatistikTransaksiError(Request $request)
    {
      $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

      $getData = DB::select("with errors as (
                  select partner_pulsa_id, left(purchase_datetime, 8) as date, left(status_remark, 50) as remark, count(*) as error_count
                  from sw_pos p inner join sw_partner_product pp using (partner_product_id)
                  where status = 'E'
                  and purchase_datetime like '$tahun_bulan%'
                  group by partner_pulsa_id,left(purchase_datetime, 8), left(status_remark, 50)
                ), success as (
                  select partner_pulsa_id, left(purchase_datetime, 8) as date, count(*) as success_count
                  from sw_pos p inner join sw_partner_product pp using (partner_product_id)
                  where status = 'S'
                  and purchase_datetime like '$tahun_bulan%'
                  group by partner_pulsa_id, left(purchase_datetime, 8)
                )
                select p.partner_pulsa_name as partner_name, to_date(e.date, 'YYYYMMDDHH24MISS') as date, e.remark, e.error_count, s.success_count,  (error_count::numeric / success_count::numeric * 100)::numeric(14,2) as presentase 
                from errors e 
                right join success s using (partner_pulsa_id, date) 
                inner join sw_partner_pulsa p using (partner_pulsa_id) 
                order by p.partner_pulsa_name, e.date");

      $getData = array_map(function ($value) {
          return (array)$value;
      }, $getData);

      if(!$getData){
        return redirect()->route('report.statistikTransaksiError')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Statistik Transaksi Error', function($excel) use($getData, $request){
        $excel->sheet('Statistik Transaksi Error', function($sheet) use ($getData, $request)
        {
          $sheet->row(1, array('Bulan'));
          $sheet->row(2, array($request->tahun_bulan));
          $sheet->fromArray($getData, null, 'A4', true);
        });
      })->download('csv');

      return redirect()->route('report.statistikTransaksiError');
    }

    public function byPerubahanStatusManual()
    {
      return view('report.perubahanStatusManual');
    }

    public function postByPerubahanStatusManual(Request $request)
    {

      $f_start_date = date('YmdHis', strtotime($request->f_start_date.' 00:00:00'));
      $f_end_date = date('YmdHis', strtotime($request->f_end_date.' 23:59:59'));

      $getData = Pos::join('sw_agent as a', 'a.agent_id', 'sw_pos.agent_id')
                      ->join('sw_product as p', 'p.product_id', 'sw_pos.product_id')
                      ->join('sw_pos_status_log as log', 'log.pos_id', 'sw_pos.pos_id')
                      ->select(DB::raw('to_timestamp(sw_pos.purchase_datetime, \'YYYYMMDDHH24MISS\') as purchase_datetime'), 'a.agent_name', 'p.product_code', 'sw_pos.receiver_phone_number', DB::raw('to_timestamp(log.update_status_datetime, \'YYYYMMDDHH24MISS\') as update_status_datetime'), 'log.new_status', 'log.status_remark', 'log.internal_remark')
                      ->whereBetween('log.update_status_datetime', [$f_start_date, $f_end_date])
                      ->get()
                      ->toArray();
      
      if(!$getData){
        return redirect()->route('report.byPerubahanStatusManual')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Perubahan Status Manual', function($excel) use($getData, $request){
        $excel->sheet('Perubahan Status Manual', function($sheet) use ($getData, $request)
        {
          $sheet->row(1, array('Tanggal Mulai', 'Tanggal Berakhir'));
          $sheet->row(2, array($request->f_start_date, $request->f_end_date));
          $sheet->fromArray($getData, null, 'A4', true);
        });
      })->download('csv');

      return redirect()->route('report.byPerubahanStatusManual');
    }
}
