<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pos;

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
                        ->select('sw_partner_product.partner_product_code', 'sw_product.product_code')
                        ->where('sw_pos.purchase_datetime', 'like', '%'.$tahun_bulan.'%')->get()->toArray();

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
      # code...
    }


    public function byProvider()
    {
      return view('report.provider');
    }

    public function postByProvider(Request $request)
    {
      # code...
    }


    public function byTopUpDepositPartner()
    {
        return view('report.depositPartner');
    }

    public function postByTopUpDepositPartner(Request $request)
    {
      # code...
    }
}
