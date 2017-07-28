<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductSellPrice;
use App\Models\Product;

use DB;
use Auth;
use Validator;
use Excel;
use Input;

class ProductSellPriceController extends Controller
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


    public function index()
    {
        $getProductSellPrice = ProductSellPrice::get();

        return view('product-sell-price.index', ['getProductSellPrice' => $getProductSellPrice]);
    }

    public function tambah()
    {
        $getProduct = Product::get();

        return view('product-sell-price.tambah', compact('getProduct'));
    }

    public function bindProduct($id)
    {
        $getProduct = Product::find($id);

        return $getProduct;
    }

    public function store(Request $request)
    {
      $message = [
        'product_id.required' => 'This field is required.',
        'gross_sell_price.required' => 'This field is required.',
        'gross_sell_price.numeric' => 'Numeric Only.',
        'datetime_start.required' => 'This field is required.',
        'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
        'datetime_end.required' => 'This field is required.',
      ];

      $validator = Validator::make($request->all(), [
        'product_id' => 'required',
        'gross_sell_price' => 'required|numeric',
        'datetime_start' => 'required|before_or_equal:datetime_end',
        'datetime_end' => 'required',
      ], $message);

      if($validator->fails()){
        return redirect()->route('product-sell-price.tambah')->withErrors($validator)->withInput();
      }

      // Start Check if exist
      $cekSellPrice = ProductSellPrice::where('product_id', $request->product_id)
                                        ->whereDate('datetime_start', '>=', $request->datetime_start)
                                        ->whereDate('datetime_end', '<=', $request->datetime_end)
                                        ->first();

      if($cekSellPrice){
        return redirect()->route('product-sell-price.tambah')->withInput()->with('gagal', 'This period has been set for '.$cekSellPrice->product->product_name.' with Gross Sell Price Rp. '.number_format($cekSellPrice->gross_sell_price, 0,',','.'));
      }
      // End Check if exist

      DB::transaction(function() use($request){
        $save = New ProductSellPrice;
        $save->product_id = $request->product_id;
        $save->gross_sell_price = $request->gross_sell_price;
        $save->datetime_start = $request->datetime_start;
        $save->datetime_end = $request->datetime_end;
        if($request->flg_tax == 1){
          $save->flg_tax = 1;
          $save->tax_percentage = $request->tax_percentage;
        }
        if($request->active == "on"){
          $save->active = 1;
          $save->active_datetime = date('Y-m-d H:i:s');
        }else{
          $save->active = 0;
          $save->non_active_datetime = date('Y-m-d H:i:s');
        }
        $save->version = 1;
        $save->create_user_id = 1; //Auth::user()->id;
        $save->save();
      });


      return redirect()->route('product-sell-price.tambah')->with('berhasil', 'Your data has been successfully saved.');


    }

    public function ubah($id)
    {
        $getProductSellPrice = ProductSellPrice::find($id);

        if(!$getProductSellPrice){
          return view('errors.404');
        }

        $getProduct = Product::get();

        return view('product-sell-price.ubah', compact('id','getProductSellPrice','getProduct'));
    }

    public function update(Request $request)
    {
        $message = [
          'product_id.required' => 'This field is required.',
          'gross_sell_price.required' => 'This field is required.',
          'gross_sell_price.numeric' => 'Numeric Only.',
          'datetime_start.required' => 'This field is required.',
          'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
          'datetime_end.required' => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
          'product_id' => 'required',
          'gross_sell_price' => 'required|numeric',
          'datetime_start' => 'required|before_or_equal:datetime_end',
          'datetime_end' => 'required',
        ], $message);

        if($validator->fails()){
          return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->withErrors($validator)->withInput();
        }


        DB::transaction(function() use($request){
          $update = ProductSellPrice::find($request->product_sell_price_id);
          $update->product_id = $request->product_id;
          $update->gross_sell_price = $request->gross_sell_price;
          $update->datetime_start = $request->datetime_start;
          $update->datetime_end = $request->datetime_end;
          if($request->flg_tax == 1){
            $update->flg_tax = 1;
            $update->tax_percentage = $request->tax_percentage;
          }
          if($request->active == 1){
            $update->active = 1;
            $update->active_datetime = date('Y-m-d H:i:s');
          }else{
            $update->active = 0;
            $update->non_active_datetime = date('Y-m-d H:i:s');
          }
          $update->version = 1;
          $update->create_user_id = 1; //Auth::user()->id;
          $update->update();
        });


        return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully updated.');

    }

    public function active($id)
    {
        $getProductSellPrice = ProductSellPrice::find($id);

        if(!$getProductSellPrice){
          return view('errors.404');
        }

        if ($getProductSellPrice->active == 1) {
          $getProductSellPrice->active = 0;
          $getProductSellPrice->non_active_datetime = date('Y-m-d H:i:s');
          $getProductSellPrice->update_user_id = 1; //Auth::user()->id;
          $getProductSellPrice->update();

          return redirect()->route('product-sell-price.index')->with('berhasil', 'Successfully Nonactive'.$getProductSellPrice->gross_sell_price);
        }else{
          $getProductSellPrice->active = 1;
          $getProductSellPrice->active_datetime = date('Y-m-d H:i:s');
          $getProductSellPrice->update_user_id = 1; //Auth::user()->id;
          $getProductSellPrice->update();

          return redirect()->route('product-sell-price.index')->with('berhasil', 'Successfully Activated '.$getProductSellPrice->gross_sell_price);
        }
    }

    public function delete($id)
    {
        $getProductSellPrice = ProductSellPrice::find($id);

        if(!$getProductSellPrice){
          return view('errors.404');
        }

        $getProductSellPrice->delete();

        return redirect()->route('product-sell-price.index')->with('berhasil', 'Successfully Deleted '.$getProductSellPrice->gross_sell_price);
    }

    public function upload()
    {

        return view('product-sell-price.masal');
    }

    public function template()
    {
        $getProduct = Product::where('active', '=', 1)
                              ->select('id', 'product_name', 'nominal')
                              ->get()
                              ->toArray();

        return Excel::create('Template Import', function($excel) use($getProduct)
        {
          $excel->sheet('Data-Import', function($sheet){
            $sheet->row(1, array('product_id', 'gross_sell_price','tax_percentage', 'datetime_start', 'datetime_end'));
            $sheet->setColumnFormat(array(
              'A' => '@',
              'B' => '0.00',
              'C' => '0.00',
              'D' => 'yyyy-mm-dd hh:mm:ss',
              'E' => 'yyyy-mm-dd hh:mm:ss',
            ));
          });

          $excel->sheet('product_id', function($sheet) use($getProduct){
            $sheet->fromArray($getProduct, null, 'A6', true);
            $sheet->row(1, array('Example'));
            $sheet->mergeCells('A1:E1');
            $sheet->row(2, array('product_id', 'gross_sell_price','tax_percentage', 'datetime_start', 'datetime_end'));
            // $sheet->row(2, array('product_id', 'gross_sell_price','tax_percentage', 'datetime_start', 'datetime_end', 'active', 'version'));
            $sheet->row(3, array('1', '45000', '10', '2017-07-01 12:00:00', '2017-07-31 12:00:00'));
            $sheet->row(5, array('Data Product'));
            $sheet->mergeCells('A5:C5');
            $sheet->row(6, array('id','product_name','nominal'));
            $sheet->setAllBorders('thin');
            $sheet->setFreeze('A7');

            $sheet->cells('A2:E3', function($cells){
              $cells->setBackground('#5c92e8');
              $cells->setFontColor('#000000');
              $cells->setFontWeight('bold');
            });

            $sheet->cells('A6:C6', function($cells){
              $cells->setBackground('#000000');
              $cells->setFontColor('#ffffff');
              $cells->setFontWeight('bold');
            });

          });
        })->download('xls');
    }

    public function prosesTemplate(Request $request)
    {
      if($request->hasFile('file')){
        $path = Input::file('file')->getRealPath();
        $data = Excel::selectSheets('Data-Import')->load($path, function($reader) {
        })->get();

        if(!empty($data) && $data->count()){
          foreach ($data as $key) {
            $collect[] = ['product_id'         => $key->product_id,
                         'gross_sell_price'    => $key->gross_sell_price,
                         'tax_percentage'      => $key->tax_percentage,
                         'datetime_start'       => $key->datetime_start,
                         'datetime_end'     => $key->datetime_end,
                        //  'active'        => $key->active,
                        //  'version'=> $key->version
                       ];
          }

          if(!empty($collect)){

            $collect = collect($collect);

            $getProduct = Product::where('active', '=', 1)->get();

            return view('product-sell-price.masal', compact('collect', 'getProduct'));
          }
        }else{
          return view('product-sell-price.masal')->with('gagal', 'Please Download Template');
        }
      }else{
        return view('product-sell-price.masal')->with('gagal', 'Please Select Template');
      }
    }

    public function storeTemplate(Request $request)
    {
        // dd($request);
        $product_id = Input::get('product_id');
        $gross_sell_price = Input::get('gross_sell_price');
        $tax_percentage = Input::get('tax_percentage');
        $datetime_start = Input::get('datetime_start');
        $datetime_end = Input::get('datetime_end');
        // $active = Input::get('active');
        // $version = Input::get('version');
        // dd($product_id, $gross_sell_price, $tax_percentage, $datetime_start, $datetime_end, $active, $version);
        DB::transaction(function() use($product_id, $gross_sell_price, $tax_percentage, $datetime_start, $datetime_end){
          foreach ($product_id as $key => $n ) {
            /*Load array */
            $arrData = ProductSellPrice::create(array("product_id"  =>  $product_id[$key],
                              "gross_sell_price"  =>  $gross_sell_price[$key],
                              "tax_percentage"  => $tax_percentage[$key],
                              "datetime_start"  => $datetime_start[$key],
                              "datetime_end"  => $datetime_end[$key],
                              "create_user_id"  => 1,
                              // "active"  => $active[$key],
                              "version"  => 1,
                            ));
          }

        // dd($arrData);
          // $save = ProductSellPrice::create($arrData);
        });

        return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully uploaded.');

    }
}
