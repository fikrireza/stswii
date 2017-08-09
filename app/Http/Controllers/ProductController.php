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


	public function index(Request $request)
	{
		$provider = Provider::get();

		$message = [
			'f_provider.integer' => 'Invalid filter',
		];

		$validator = Validator::make($request->all(), [
			'f_provider' => 'integer|nullable',
		], $message);

		if($validator->fails())
		{
			return redirect()->route('product.index');
		}

		$index = Product::orderBy('product_id', 'ASC');

		if(isset($request->f_provider) && $request->f_provider != '')
		{
			$index->where('provider_id', $request->f_provider);
		}

		$index = $index->get();

		return view('product.index', compact('index', 'request', 'provider'));
	}

	public function tambah()
	{
		$provider = Provider::get();

		$rand = rand(1000,9999);

		$product_code = 'prod'.'-'.$rand;

		$cek_kode = Product::where('product_code', $product_code)->first();

		if(!$cek_kode){
			$product_code;
		}else{
			$product_code = 'Product Code is Empty - Contact Amadeo Please';
		}

		return view('product.tambah', compact('provider', 'product_code'));
	}

	public function store(Request $request)
	{
		$message = [
			'product_code.required' => 'This field required',
			'product_code.unique' => 'This code has already taken',
			'product_name.required' => 'This field required',
			'provider_id.required' => 'This field required',
			'nominal.required' => 'This field required',
			'nominal.numeric' => 'Fill Numeric',
			'type.required' => 'This field required',
		];

		$validator = Validator::make($request->all(), [
			'product_code' => 'required|unique:sw_product',
			'product_name' => 'required',
			'provider_id' => 'required',
			'nominal' => 'required|numeric',
			'type' => 'required',
		], $message);

		if($validator->fails())
		{
			return redirect()->route('product.tambah')->withErrors($validator)->withInput();
		}

		$index = new Product;

		$index->product_code = $request->product_code;
		$index->product_name = $request->product_name;
		$index->provider_id  = $request->provider_id;
		$index->nominal      = $request->nominal;
		$index->type = $request->type;

		$index->active = isset($request->active) ? 1 : 0;

		if(isset($request->active))
		{
			$index->active_datetime = date('YmdHis');
			$index->non_active_datetime = 00000000000000;
		}
		else
		{
			$index->active_datetime = 00000000000000;
			$index->non_active_datetime = date('YmdHis');
		}


		$index->version = 0;
		$index->create_datetime = date('YmdHis');
		$index->create_user_id = Auth::id();
		$index->update_datetime = 00000000000000;
		$index->update_user_id = 0;

		$index->save();

		return redirect()->route('product.index')->with('berhasil', 'Your data has been successfully saved.');
	}

	public function ubah($product_code)
	{
		$index = Product::where('product_code',$product_code)->first();

		$provider = Provider::get();

		return view('product.ubah', compact('index', 'provider'));
	}

	public function update(Request $request)
	{
		$message = [
			'provider_id.required' => 'This field required',
			'product_code.required' => 'This field required',
			'product_code.unique' => 'This code has already taken',
			'product_name.required' => 'This field required',
			'nominal.required' => 'This field required',
			'nominal.numeric' => 'Fill Numeric',
			'type.required' => 'This field required',
		];

		$validator = Validator::make($request->all(), [
			// 'product_code' => 'required|unique:sw_product,product_code,'.$request->product_id,
			'product_name' => 'required',
			'provider_id' => 'required',
			'nominal' => 'required|numeric',
			'type' => 'required',
		], $message);

		if($validator->fails())
		{
			return redirect()->route('product.ubah', ['product_code' => $request->product_code])->withErrors($validator)->withInput();
		}

		$index = Product::where('product_id', $request->product_id)->first();

		if($index->version != $request->version)
		{
			return redirect()->route('product.ubah', ['product_code' => $request->product_code])->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
		}

		$index->product_code = $request->product_code;
		$index->product_name = $request->product_name;
		$index->provider_id  = $request->provider_id;
		$index->nominal      = $request->nominal;
		$index->type = $request->type;

		$index->active = isset($request->active) ? 1 : 0;

		if(isset($request->active))
		{
			$index->active_datetime = date('YmdHis');
		}
		else
		{
			$index->non_active_datetime = date('YmdHis');
		}

		$index->version += 1;
		$index->update_datetime = date('YmdHis');
		$index->update_user_id = Auth::id();

		$index->save();

		return redirect()->route('product.index')->with('berhasil', 'Your data has been successfully updated.');
	}

	public function active($id, Request $request)
	{
		$index = Product::where('product_id', $id)->first();

		if($index->version != $request->version)
		{
			return redirect()->route('product.index')->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
		}

		if ($index->active) {

			$index->active = 0;
			$index->non_active_datetime = date('YmdHis');

			$index->version += 1;
			$index->update_datetime = date('YmdHis');
			$index->update_user_id = Auth::id();

			$index->save();

			return redirect()->route('product.index')->with('berhasil', 'Successfully Nonactive');
		}else{

			$index->active = 1;
			$index->active_datetime = date('YmdHis');

			$index->version += 1;
			$index->update_datetime = date('YmdHis');
			$index->update_user_id = Auth::id();

			$index->save();

			return redirect()->route('product.index')->with('berhasil', 'Successfully Activated ');
		}
	}

	public function delete($id)
	{
		$index = Product::where('product_id', $id)->delete();

		return redirect()->route('product.index')->with('berhasil', 'Successfully Deleted ');
	}

}
