<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

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

			return view('product.tambah', compact('provider'));
		}

		public function store(Request $request)
		{
			$message = [
				'product_code.required' => 'This field required',
				'product_code.unique' => 'This code has already taken',
				'product_name.required' => 'This field required',
				'provider_id.required' => 'This field required',
				'nominal.required' => 'This field required',
				'type.required' => 'This field required',
			];

			$validator = Validator::make($request->all(), [
				'product_code' => 'required|unique:sw_product',
				'product_name' => 'required',
				'provider_id' => 'required',
				'nominal' => 'required',
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
			$index->nominal      = str_replace('.','',$request->nominal);
			$index->type = $request->type;

			$index->active = isset($request->active) ? "Y" : "N";

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
				'type.required' => 'This field required',
			];

			$validator = Validator::make($request->all(), [
				'product_code' => 'required|unique:sw_product,product_code,'.$request->product_id.',product_id',
				'product_name' => 'required',
				'provider_id' => 'required',
				'nominal' => 'required',
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

			$index->product_code = strtoupper($request->product_code);
			$index->product_name = $request->product_name;
			$index->provider_id  = $request->provider_id;
			$index->nominal      = str_replace('.', '',$request->nominal);
			$index->type = $request->type;

			$index->active = isset($request->active) ? "Y" : "N";

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
					return redirect()->back()->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
				}

				if ($index->active == "Y") {

					$index->active = "N";
					$index->non_active_datetime = date('YmdHis');

					$index->version += 1;
					$index->update_datetime = date('YmdHis');
					$index->update_user_id = Auth::id();

					$index->save();

					return redirect()->back()->with('berhasil', 'Successfully Nonactive');
				}else{

					$index->active = "Y";
					$index->active_datetime = date('YmdHis');

					$index->version += 1;
					$index->update_datetime = date('YmdHis');
					$index->update_user_id = Auth::id();

					$index->save();

					return redirect()->back()->with('berhasil', 'Successfully Activated ');
				}
		}

		public function delete($id)
		{
				$index = Product::where('product_id', $id)->delete();

				return redirect()->back()->with('berhasil', 'Successfully Deleted ');
		}

		public function yajraGetData(Request $request)
		{
				$f_provider = $request->query('f_provider');

	    	$getProducts = Product::leftJoin('sw_provider', 'sw_provider.provider_id', 'sw_product.provider_id')
		    	->select(['sw_provider.provider_code as provider_code','sw_product.provider_id as provider_id','product_id','product_code','product_name','nominal','type','active','sw_product.version']);

	    	if($f_provider != null)
				{
					$getProducts->where('sw_product.provider_id', $f_provider);
				}

	    	$getProducts = $getProducts->get();

	    	$start=1;

	      $Datatables = Datatables::of($getProducts)
          ->addColumn('slno', function ($getProduct) use (&$start)
						{
	              return $start++;
	          })
          ->editColumn('nominal',  function ($getProduct)
						{
	              return 'Rp. '.number_format($getProduct->nominal, 2);
	          })
	        ->addColumn('action', function ($getProduct)
						{
		        	$actionHtml = '';
							if (Auth::user()->can('update-product')) {
								$actionHtml = $actionHtml." <a href='".route('product.ubah',$getProduct->product_code)."'' class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></a>";
							}
							if(Auth::user()->can('activate-product')){
								if($getProduct->active == "Y"){
									$actionHtml = $actionHtml."<a href='' class='unpublish' data-value='".$getProduct->product_id."' data-version='".$getProduct->version."' data-toggle='modal' data-target='.modal-nonactive'><span class='btn btn-dark btn-xs btn-sm' data-toggle='tooltip' data-placement='top' title='Non Active'><i class='fa fa-times'></i></span></a>";
								}else{
									$actionHtml = $actionHtml."<a href='' class='publish' data-value='".$getProduct->product_id."' data-version='".$getProduct->version."' data-toggle='modal' data-target='.modal-active'><span class='btn btn-success btn-xs btn-sm' data-toggle='tooltip' data-placement='top' title='Active'><i class='fa fa-check'></i></span></a>";
								}
							}
							if (Auth::user()->can('delete-product')) {
								$actionHtml = $actionHtml." <a href='' class='delete' data-value='".$getProduct->product_id."' data-version='".$getProduct->version."' data-toggle='modal' data-target='.modal-delete'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></span></a>";
							}

							return $actionHtml;
		        });

				if (Auth::user()->can('activate-product'))
				{
					$Datatables = $Datatables->editColumn('active', function ($getProduct)
					{
						if($getProduct->active == "Y")
						{
							return "
								<a
									href=''
									class='unpublish'
									data-value='".$getProduct->product_id."'
									data-version='".$getProduct->version."'
									data-toggle='modal'
									data-target='.modal-nonactive'
								><span class='label label-success' data-toggle='tooltip' data-placement='top' title='Active'>Active</span>
								</a><br>";
						}
						else
						{
							return "
								<a
									href=''
									class='publish'
									data-value='".$getProduct->product_id."'
									data-version='".$getProduct->version."'
									data-toggle='modal'
									data-target='.modal-active'
								><span class='label label-danger' data-toggle='tooltip' data-placement='top' title='Non Active'>Non Active</span>
								</a><br>";
						}
		      });
				}
				else
				{
		      $Datatables = $Datatables->removeColumn('active');
				}

      $Datatables = $Datatables
      	->removeColumn('provider_id')
          ->removeColumn('product_id')
          ->removeColumn('version')
          ->escapeColumns(['*'])
          ->make(true);

      return $Datatables;
	  }
}
