<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pos;
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
                        ->leftjoin('sw_partner_product', 'sw_partner_product.partner_product_id', '=', 'sw_pos.partner_product_id')
                        ->leftjoin('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', '=', 'sw_partner_product.partner_pulsa_id')
                        ->select('sw_partner_pulsa.partner_pulsa_code','sw_partner_product.partner_product_code', 'sw_product.product_code', 
                          DB::raw('COUNT(sw_pos.pos_id) as qty'), 
                          DB::raw('SUM(sw_pos.gross_purch_price) as amount_buy'),
                          DB::raw('SUM(sw_pos.gross_sell_price) as amount_sell'),
                          DB::raw('SUM(sw_pos.gross_sell_price) - SUM(sw_pos.gross_purch_price) as profit'))
                        ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                        ->where('sw_pos.status', 'S')
                        ->where('sw_partner_pulsa.flg_pkp', 'Y')
                        ->groupBy(['sw_partner_pulsa.partner_pulsa_code','sw_partner_product.partner_product_code','sw_product.product_code'])
                        ->get()
                        ->toArray();

        if(!$getData){
          return redirect()->route('report.bySupplierPkp')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Sales By Supplier PKP - '.$request->tahun_bulan, function($excel) use($getData){
          $excel->sheet('Sales By Supplier PKP', function($sheet) use ($getData)
          {
            $sheet->fromArray($getData, null, 'A1', true);
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
                        ->leftjoin('sw_partner_product', 'sw_partner_product.partner_product_id', '=', 'sw_pos.partner_product_id')
                        ->leftjoin('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', '=', 'sw_partner_product.partner_pulsa_id')
                        ->select('sw_partner_pulsa.partner_pulsa_code','sw_partner_product.partner_product_code', 'sw_product.product_code', 
                          DB::raw('COUNT(sw_pos.pos_id) as qty'), 
                          DB::raw('SUM(sw_pos.gross_purch_price) as amount_buy'),
                          DB::raw('SUM(sw_pos.gross_sell_price) as amount_sell'),
                          DB::raw('SUM(sw_pos.gross_sell_price) - SUM(sw_pos.gross_purch_price) as profit'))
                        ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                        ->where('sw_pos.status', 'S')
                        ->where('sw_partner_pulsa.flg_pkp', 'N')
                        ->groupBy(['sw_partner_pulsa.partner_pulsa_code','sw_partner_product.partner_product_code','sw_product.product_code'])
                        ->get()
                        ->toArray();

        if(!$getData){
          return redirect()->route('report.bySupplierNonPkp')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Sales By Supplier Non PKP - '.$request->tahun_bulan, function($excel) use($getData){
          $excel->sheet('Sales By Supplier Non PKP', function($sheet) use ($getData)
          {
            $sheet->fromArray($getData, null, 'A1', true);
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
                        ->select('sw_agent.agent_name', 'sw_agent.phone_number', 'sw_product.product_code', 'sw_provider.provider_code', DB::raw('COUNT(sw_pos.pos_id) as qty'), DB::raw('SUM(sw_pos.gross_sell_price) as amount'))
                        ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                        ->where('sw_pos.status', 'S')
                        ->groupBy(['sw_agent.agent_name', 'sw_agent.phone_number','sw_product.product_code', 'sw_provider.provider_code'])
                        ->orderBy('sw_agent.agent_name')
                        ->orderBy('sw_product.product_code')
                        ->orderBy('sw_provider.provider_code')
                        ->get()
                        ->toArray();

        if(!$getData){
          return redirect()->route('report.byAgent')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Sales By Agent - '.$request->tahun_bulan, function($excel) use($getData){
          $excel->sheet('Sales By Agent', function($sheet) use ($getData)
          {
            $sheet->fromArray($getData, null, 'A1', true);
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
                      ->select('sw_product.product_code', 'sw_provider.provider_code', DB::raw('COUNT(sw_pos.pos_id) as qty'), DB::raw('SUM(sw_pos.gross_sell_price) as amount_buy'), DB::raw('SUM(sw_pos.gross_purch_price) as amount_sell'), DB::raw('SUM(sw_pos.gross_sell_price - sw_pos.gross_purch_price) as profit'))
                      ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                      ->where('sw_pos.status', 'S')
                      ->groupBy(['sw_product.product_code', 'sw_provider.provider_code'])
                      ->orderBy('sw_product.product_code')
                      ->orderBy('sw_provider.provider_code')
                      ->get()
                      ->toArray();

      if(!$getData){
        return redirect()->route('report.byProvider')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Sales By Provider - '.$request->tahun_bulan, function($excel) use($getData){
        $excel->sheet('Sales By Provider', function($sheet) use ($getData)
        {
          $sheet->fromArray($getData, null, 'A1', true);
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

    public function byRekapSalesHarianAgent()
    {
        return view('report.rekapSalesHarianAgent');
    }

    public function postByRekapSalesHarianAgent(Request $request)
    {
      $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

      $getData = Pos::join('sw_agent', 'sw_agent.agent_id', '=', 'sw_pos.agent_id')
                      ->select('sw_agent.agent_name', 'sw_agent.phone_number', DB::raw('to_date(sw_pos.purchase_datetime, \'YYYYMMDDHH24MISS\') as tanggal'), DB::raw('SUM(sw_pos.gross_sell_price) as jumlah_amount'), DB::raw('COUNT(*) as jumlah_trx'))
                      ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                      ->where('sw_pos.status', 'S')
                      ->groupBy(['sw_agent.agent_name', 'sw_agent.phone_number', 'tanggal'])
                      ->orderBy('sw_agent.agent_name')
                      ->orderBy('tanggal')
                      ->get()
                      ->toArray();

      if(!$getData){
        return redirect()->route('report.byRekapSalesHarianAgent')->with('gagal', 'Data Not Found')->withInput();
      }

      return Excel::create('Report Rekap Sales Harian Agent - '.$request->tahun_bulan, function($excel) use($getData){
        $excel->sheet('Rekap Sales Harian Agent', function($sheet) use ($getData)
        {
          $sheet->fromArray($getData, null, 'A1', true);
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

      return Excel::create('Report Weekly Sales Summary- '.$request->tahun_bulan, function($excel) use($getData){
        $excel->sheet('Weekly Sales Summary', function($sheet) use ($getData)
        {
          $sheet->fromArray($getData, null, 'A1', true);
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

      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', 'http://localhost:9020/report/agentBalance')
          ->getbody();

          $result = json_decode($res);
          $getData = [];

          foreach ($result as $value) {
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
    }
}
