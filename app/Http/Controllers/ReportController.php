<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pos;

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

    public function bySupplier()
    {

      return view('report.supplier');
    }

    public function postBySupplier(Request $request)
    {

        $tahun_bulan = date('Ym', strtotime($request->tahun_bulan));

        $getData = Pos::leftjoin('sw_product', 'sw_product.product_id', '=', 'sw_pos.product_id')
                        ->leftjoin('sw_partner_product', 'sw_partner_product.partner_product_id', '=', 'sw_pos.partner_product_id')
                        ->select('sw_partner_product.partner_product_code', 'sw_product.product_code', DB::raw('COUNT(sw_pos.pos_id) as qty'), DB::raw('SUM(sw_pos.gross_sell_price) as amount'))
                        ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                        ->where('status', 'S')
                        ->groupBy(['sw_partner_product.partner_product_code','sw_product.product_code'])
                        ->get()
                        ->toArray();

        if(!$getData){
          return redirect()->route('report.bySupplier')->with('gagal', 'Data Not Found')->withInput();
        }

        return Excel::create('Report Sales By Supplier - '.$request->tahun_bulan, function($excel) use($getData){
          $excel->sheet('Sales By Supplier', function($sheet) use ($getData)
          {
            $sheet->fromArray($getData, null, 'A1', true);
          });
        })->download('csv');

        return redirect()->route('report.bySupplier');
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
                        ->select('sw_agent.agent_name', 'sw_product.product_code', DB::raw('COUNT(sw_pos.pos_id) as qty'), DB::raw('SUM(sw_pos.gross_sell_price) as amount'))
                        ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                        ->groupBy(['sw_agent.agent_name','sw_product.product_code'])
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
                      ->select('sw_product.product_code', DB::raw('COUNT(sw_pos.pos_id) as qty'), DB::raw('SUM(sw_pos.gross_sell_price) as amount_buy'), DB::raw('SUM(sw_pos.gross_purch_price) as amount_sell'), DB::raw('SUM(sw_pos.gross_sell_price - sw_pos.gross_purch_price) as profit'))
                      ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')
                      ->groupBy(['sw_product.product_code'])
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
}
