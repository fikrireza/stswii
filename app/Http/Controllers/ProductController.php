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
          'product_name.unique' => 'This name has already taken',
          'nominal.required' => 'This field required',
          'nominal.numeric' => 'Fill Numeric',
        ];

        $validator = Validator::make($request->all(), [
          'product_code' => 'required|unique:amd_products',
          'product_name' => 'required|unique:amd_products',
          'provider_id' => 'required',
          'nominal' => 'required|numeric',
        ], $message);

        if($validator->fails())
        {
          return redirect()->route('product.tambah')->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
          $save = New Product;
          $save->product_code = $request->product_code;
          $save->product_name = $request->product_name;
          $save->provider_id = $request->provider_id;
          $save->nominal = $request->nominal;
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


        return redirect()->route('product.tambah')->with('berhasil', 'Your data has been successfully saved.');
    }

    public function ubah($product_code)
    {
        $getProduct = Product::where('product_code', $product_code)->first();

        if(!$getProduct){
          return view('errors.404');
        }

        $getProvider = Provider::get();

        return view('product.ubah', compact('getProduct','getProvider'));
    }

    public function update(Request $request)
    {
        $message = [
          'product_code.required' => 'This field required',
          'product_code.unique' => 'This code has already taken',
          'product_name.required' => 'This field required',
          'product_name.unique' => 'This name has already taken',
          'nominal.required' => 'This field required',
          'nominal.numeric' => 'Fill Numeric',
        ];

        $validator = Validator::make($request->all(), [
          'product_code' => 'required|unique:amd_products,product_code,'.$request->product_id,
          'product_name' => 'required|unique:amd_products,product_name,'.$request->product_id,
          'provider_id' => 'required',
          'nominal' => 'required|numeric',
        ], $message);

        if($validator->fails())
        {
          return redirect()->route('product.ubah', ['product_code' => $request->product_code])->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
          $update = Product::find($request->product_id);
          $update->product_code = $request->product_code;
          $update->product_name = $request->product_name;
          $update->provider_id = $request->provider_id;
          $update->nominal = $request->nominal;
          if($request->active == "on"){
            $update->active = 1;
            $update->active_datetime = date('Y-m-d H:i:s');
          }else{
            $update->active = 0;
            $update->non_active_datetime = date('Y-m-d H:i:s');
          }
          $update->version = +1;
          $update->update_user_id = 1; //Auth::user()->id;
          $update->update();
        });


        return redirect()->route('product.index')->with('berhasil', 'Your data has been successfully updated.');
    }

    public function active($id)
    {
        $getProduct = Product::find($id);

        if(!$getProduct){
          return view('errors.404');
        }

        if ($getProduct->active == 1) {
          $getProduct->active = 0;
          $getProduct->non_active_datetime = date('Y-m-d H:i:s');
          $getProduct->update_user_id = 1; //Auth::user()->id;
          $getProduct->update();

          return redirect()->route('product.index')->with('berhasil', 'Successfully Nonactive'.$getProduct->product_name);
        }else{
          $getProduct->active = 1;
          $getProduct->active_datetime = date('Y-m-d H:i:s');
          $getProduct->update_user_id = 1; //Auth::user()->id;
          $getProduct->update();

          return redirect()->route('product.index')->with('berhasil', 'Successfully Activated '.$getProduct->product_name);
        }
    }

    public function delete($id)
    {
        $getProduct = Product::find($id);

        if(!$getProduct){
          return view('errors.404');
        }

        $getProduct->delete();

        return redirect()->route('product.index')->with('berhasil', 'Successfully Deleted '.$getProduct->product_name);
    }

}
