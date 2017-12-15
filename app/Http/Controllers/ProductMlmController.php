<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Product;
use App\Models\ProductMlm;
use App\Models\Provider;

use Auth;
use DB;
use Validator;

class ProductMlmController extends Controller
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

				$index = DB::table('sw_product_mlm as a')
						->join('sw_product as b', 'b.product_id', '=', 'a.product_id')
						->orderBy('a.product_id', 'ASC');

				if(isset($request->f_provider) && $request->f_provider != '')
				{
					$index->where('b.provider_id', $request->f_provider);
				}

				$index = $index->get();

				return view('product-mlm.index', compact('index', 'request', 'provider'));
		}

		public function tambah()
		{
			$product = Product::whereNotIn('product_id', function($query){
							$query->select('product_id')->from('sw_product_mlm');
						})->get();

			return view('product-mlm.tambah', compact('product'));
		}

		public function store(Request $request)
		{
			$message = [
				'product_id.required' => 'This field required'	
			];

			$validator = Validator::make($request->all(), [
				'product_id' => 'required'
			], $message);

			if($validator->fails())
			{
				return redirect()->route('product-mlm.tambah')->withErrors($validator)->withInput();
			}

			$index = new ProductMlm;
			$index->product_id  = $request->product_id;
			$index->version = 0;
			$index->create_datetime = date('YmdHis');
			$index->create_user_id = Auth::id();
			$index->update_datetime = '';
			$index->update_user_id = -99;

			$index->save();

			return redirect()->route('product-mlm.index')->with('berhasil', 'Your data has been successfully saved.');
		}

		public function delete($id)
		{
				$index = ProductMlm::find($id)->delete();

				return redirect()->back()->with('berhasil', 'Successfully Deleted ');
		}

		public function yajraGetData(Request $request)
		{
				$f_provider = $request->query('f_provider');
				$f_type_product = $request->query('f_type_product');

	    	$getProducts = DB::table('sw_product_mlm as a')
	    			->join('sw_product as b', 'b.product_id', '=', 'a.product_id')
	    			->leftJoin('sw_provider as c', 'c.provider_id', 'b.provider_id')
		    		->select(['c.provider_code as provider_code','b.provider_id as provider_id','a.product_id','product_code','product_name','nominal','type', 'sort_number','active','a.version']);

	    	if($f_provider != null)
				{
					$getProducts->where('b.provider_id', $f_provider);
				}

			if($f_type_product != null)
				{
					$getProducts->where('b.type', $f_type_product);
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
          ->editColumn('active',  function ($getProduct)
			{
	            if($getProduct->active == "Y")
						{
							return "
								<span class='label label-success' data-toggle='tooltip' data-placement='top' title='Active'>Active</span>";
						}
						else
						{
							return "
								<span class='label label-danger' data-toggle='tooltip' data-placement='top' title='Non Active'>Non Active</span>";
						}
          })
        ->addColumn('action', function ($getProduct)
					{
        	$actionHtml = '';
			
			if (Auth::user()->can('delete-product')) {
				$actionHtml = $actionHtml." <a href='' class='delete' data-value='".$getProduct->product_id."' data-version='".$getProduct->version."' data-toggle='modal' data-target='.modal-delete'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></span></a>";
			}

			return $actionHtml;
		});


      $Datatables = $Datatables
      	->removeColumn('provider_id')
          ->removeColumn('product_id')
          ->removeColumn('version')
          ->escapeColumns(['*'])
          ->make(true);

      return $Datatables;
	  }
}
