<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Provider;

use Auth;
use DB;
use Validator;

class ProductController extends Controller
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
        $getProduct = Product::get();

        return view('product.index', compact('getProduct'));
    }

    public function tambah()
    {
        $getProvider = Provider::get();

        $tahun = date('y');
        $bulan = date('m');
        $rand = rand(1000,9999);

        $product_code = 'SPT-'.$tahun.'-'.$bulan.'-'.$rand;

        $cek_kode = Product::where('product_code', $product_code)->first();

        if(!$cek_kode){
          $product_code;
        }else{
          $product_code = 'Product Code is Empty - Contact Amadeo Please';
        }

        return view('product.tambah', compact('getProvider', 'product_code'));
    }

    public function store(Request $request)
    {
        $message = [
          'product_code.required' => 'This field required',
          'product_code.unique' => 'This code has already taken',
          'product_name.required' => 'This field required',
          'nominal.required' => 'This field required',
          'nominal.numeric' => 'Fill Numeric',
          'type_product.required' => 'This field required',
        ];

        $validator = Validator::make($request->all(), [
          'product_code' => 'required|unique:sw_product',
          'product_name' => 'required',
          'nominal' => 'required|numeric',
          'type_product' => 'required',
        ], $message);

        if($validator->fails())
        {
          return redirect()->route('product.tambah')->withErrors($validator)->withInput();
        }

        return redirect()->route('product.index')->with('berhasil', 'Your data has been successfully saved.');
    }

    public function ubah($product_code)
    {
        return view('product.ubah');
    }

    public function update(Request $request)
    {
        $message = [
          'product_code.required' => 'This field required',
          // 'product_code.unique' => 'This code has already taken',
          'product_name.required' => 'This field required',
          'nominal.required' => 'This field required',
          'nominal.numeric' => 'Fill Numeric',
          'type_product.required' => 'This field required',
        ];

        $validator = Validator::make($request->all(), [
          // 'product_code' => 'required|unique:sw_product,product_code,'.$request->product_id,
          'product_name' => 'required',
          'nominal' => 'required|numeric',
          'type_product' => 'required',
        ], $message);

        if($validator->fails())
        {
          return redirect()->route('product.ubah', ['product_code' => 1])->withErrors($validator)->withInput();
        }

        return redirect()->route('product.index')->with('berhasil', 'Your data has been successfully updated.');
    }

    public function active($id)
    {
        if (1) {
          return redirect()->route('product.index')->with('berhasil', 'Successfully Nonactive');
        }else{
          return redirect()->route('product.index')->with('berhasil', 'Successfully Activated ');
        }
    }

    public function delete($id)
    {
        return redirect()->route('product.index')->with('berhasil', 'Successfully Deleted ');
    }

}
