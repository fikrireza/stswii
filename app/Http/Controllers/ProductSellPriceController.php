<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductSellPrice;
use App\Models\Product;
use App\Models\Provider;

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


	public function index(Request $request)
	{
		$provider = Provider::get();

		$message = [
			'f_provider.integer' => 'Invalid filter',
			'f_active.integer' => 'Invalid filter',
			'f_active.in' => 'Invalid filter',
		];

		$validator = Validator::make($request->all(), [
			'f_provider' => 'integer|nullable',
			'f_active' => 'integer|nullable|in:0,1',
		], $message);

		if($validator->fails())
		{
			return redirect()->route('product-sell-price.index');
		}

		$index = ProductSellPrice::join('sw_product', 'sw_product.product_id', '=', 'sw_product_sell_price.product_id')
			->select('sw_product_sell_price.*');

		if(isset($request->f_provider) && $request->f_provider != '')
		{
			$index->where('sw_product.provider_id', $request->f_provider);
		}

		$index->where('sw_product_sell_price.active', isset($request->f_active) ? $request->f_active : 1);

		$index = $index->get();

		return view('product-sell-price.index', compact('index', 'request', 'provider'));
	}

	public function tambah()
	{
		$product = Product::get();

		return view('product-sell-price.tambah', compact('product'));
	}

	public function bindProduct($id)
	{
		$index = Product::find($id);

		return $index;
	}

	public function store(Request $request)
	{
		$message = [
			'product_id.required' => 'This field is required.',
			'gross_sell_price.required' => 'This field is required.',
			'gross_sell_price.numeric' => 'Numeric Only.',
			'tax_percentage.required_if' => 'This field is required.',
			'datetime_start.required' => 'This field is required.',
			'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
			'datetime_end.required' => 'This field is required.',
		];

		$validator = Validator::make($request->all(), [
			'product_id' => 'required',
			'gross_sell_price' => 'required|numeric',
			'tax_percentage' => 'required_if:flg_tax,1',
			'datetime_start' => 'required|before_or_equal:datetime_end',
			'datetime_end' => 'required',
		], $message);

		if($validator->fails()){
			return redirect()->route('product-sell-price.tambah')->withErrors($validator)->withInput();
		}

		$checkData = ProductSellPrice::where('product_id',$request->product_id)
			->where('active',1)
			->get();

		foreach ($checkData as $list)
		{
			if($list->datetime_start <= date('YmdHis', strtotime($request->datetime_start)) && date('YmdHis', strtotime($request->datetime_start)) <= $list->datetime_end && isset($request->active))
			{
				return redirect()->route('product-sell-price.tambah')->with('gagal', 'Data is still active.')->withInput();
			}
			if($list->datetime_start <= date('YmdHis', strtotime($request->datetime_end)) && date('YmdHis', strtotime($request->datetime_end)) <= $list->datetime_end && isset($request->active))
			{
				return redirect()->route('product-sell-price.tambah')->with('gagal', 'Data is still active.')->withInput();
			}
		}

		$index = new ProductSellPrice;

		$index->product_id          = $request->product_id;
		$index->gross_sell_price    = $request->gross_sell_price;
		$index->flg_tax             = isset($request->flg_tax) ? 1 : 0;
		$index->tax_percentage      = isset($request->flg_tax) ? $request->tax_percentage : 0;
		$index->datetime_start      = date('YmdHis', strtotime($request->datetime_start));
		$index->datetime_end        = date('YmdHis', strtotime($request->datetime_end));

		$index->active              = isset($request->active) ? 1 : 0;
		if(isset($request->active))
		{
			$index->active_datetime     = date('YmdHis');
			$index->non_active_datetime = 00000000000000;
		}
		else
		{
			$index->active_datetime     = 00000000000000;
			$index->non_active_datetime = date('YmdHis');
		}


		$index->version             = 0;
		$index->create_datetime     = date('YmdHis');
		$index->create_user_id      = Auth::id();
		$index->update_datetime     = 00000000000000;
		$index->update_user_id      = 0;

		$index->save();

		return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully saved.');
	}

	public function ubah($id)
	{
		$index = ProductSellPrice::find($id);

		$product = Product::get();

		return view('product-sell-price.ubah', compact('index','product'));
	}

	public function update(Request $request)
	{
		$message = [
			'product_id.required' => 'This field is required.',
			'gross_sell_price.required' => 'This field is required.',
			'gross_sell_price.numeric' => 'Numeric Only.',
			'tax_percentage.required_if' => 'This field is required.',
			'datetime_start.required' => 'This field is required.',
			'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
			'datetime_end.required' => 'This field is required.',
		];

		$validator = Validator::make($request->all(), [
			'product_id' => 'required',
			'gross_sell_price' => 'required|numeric',
			'tax_percentage' => 'required_if:flg_tax,1',
			'datetime_start' => 'required|before_or_equal:datetime_end',
			'datetime_end' => 'required',
		], $message);

		if($validator->fails()){
			return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->withErrors($validator)->withInput();
		}

		$index = ProductSellPrice::find($request->product_sell_price_id);



		$checkData = ProductSellPrice::where('product_id',$request->product_id)
			->where('active',1)
			->where('product_sell_price_id','<>',$request->product_sell_price_id)
			->get();

		foreach ($checkData as $list)
		{
			if($list->datetime_start <= date('YmdHis', strtotime($request->datetime_start)) && date('YmdHis', strtotime($request->datetime_start)) <= $list->datetime_end && isset($request->active));
			{
				return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Data is still active.')->withInput();
			}
			if($list->datetime_start <= date('YmdHis', strtotime($request->datetime_end)) && date('YmdHis', strtotime($request->datetime_end)) <= $list->datetime_end && isset($request->active));
			{
				return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Data is still active.')->withInput();
			}
		}

		if($index->version != $request->version)
		{
			return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
		}

		$index->product_id          = $request->product_id;
		$index->gross_sell_price    = $request->gross_sell_price;
		$index->flg_tax             = isset($request->flg_tax) ? 1 : 0;
		$index->tax_percentage      = isset($request->flg_tax) ? $request->tax_percentage : 0;
		$index->datetime_start      = date('YmdHis', strtotime($request->datetime_start));
		$index->datetime_end        = date('YmdHis', strtotime($request->datetime_end));

		$index->active              = isset($request->active) ? 1 : 0;
		if(isset($request->active))
		{
			$index->active_datetime     = date('YmdHis');
		}
		else
		{
			$index->non_active_datetime = date('YmdHis');
		}


		$index->version             += 1;
		$index->update_datetime     = date('YmdHis');
		$index->update_user_id      = Auth::id();

		$index->save();

		return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully updated.');

	}

	public function active($id, Request $request)
	{
		$index = ProductSellPrice::find($id);

		$checkData = ProductSellPrice::where('product_id',$index->product_id)
			->where('active',1)
			->get();

		foreach ($checkData as $list)
		{
			if($list->datetime_start <= date('YmdHis', strtotime($index->datetime_start)) && date('YmdHis', strtotime($index->datetime_start)) <= $list->datetime_end && !$index->active)
			{
				return redirect()->route('product-sell-price.index')->with('gagal', 'Data is still active.')->withInput();
			}
			if($list->datetime_start <= date('YmdHis', strtotime($index->datetime_end)) && date('YmdHis', strtotime($index->datetime_end)) <= $list->datetime_end && !$index->active)
			{
				return redirect()->route('product-sell-price.index')->with('gagal', 'Data is still active.')->withInput();
			}
		}

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

			return redirect()->route('product-sell-price.index')->with('berhasil', 'Successfully Nonactive');
		}else{

			$index->active = 1;
			$index->active_datetime = date('YmdHis');

			$index->version += 1;
			$index->update_datetime = date('YmdHis');
			$index->update_user_id = Auth::id();

			$index->save();

			return redirect()->route('product-sell-price.index')->with('berhasil', 'Successfully Activated ');
		}
	}

	public function delete($id)
	{
		$index = ProductSellPrice::find($id);

		$index->delete();

		return redirect()->route('product-sell-price.index')->with('berhasil', 'Successfully Deleted ');
	}

	public function upload()
	{
		return view('product-sell-price.masal');
	}

	public function template()
	{
		$getProduct = Product::join('sw_provider', 'sw_provider.provider_id', '=', 'sw_product.provider_id')->where('sw_product.active', '=', 1)
			->select('sw_product.product_id', 'sw_product.product_name', 'sw_product.nominal', 'sw_provider.provider_name')
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
					'D' => '@',
					'E' => '@',
				));
			});

			$excel->sheet('product_id', function($sheet) use($getProduct){
				$sheet->fromArray($getProduct, null, 'A6', true);
				$sheet->row(1, array('Example'));
				$sheet->mergeCells('A1:E1');
				$sheet->row(2, array('product_id', 'gross_sell_price','tax_percentage', 'datetime_start', 'datetime_end'));
				// $sheet->row(2, array('product_id', 'gross_sell_price','tax_percentage', 'datetime_start', 'datetime_end', 'active', 'version'));
				$sheet->row(3, array('1', '45000', '10', '20170701120000', '20170731120000'));
				$sheet->row(5, array('Data Product'));
				$sheet->mergeCells('A5:C5');
				$sheet->row(6, array('id','product_name','nominal', 'provider_name'));
				$sheet->setAllBorders('thin');
				$sheet->setFreeze('A7');

				$sheet->cells('A2:E3', function($cells){
					$cells->setBackground('#5c92e8');
					$cells->setFontColor('#000000');
					$cells->setFontWeight('bold');
				});

				$sheet->cells('A6:D6', function($cells){
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
					$collect[] = [
						'product_id'       => $key->product_id,
						'gross_sell_price' => $key->gross_sell_price,
						'tax_percentage'   => $key->tax_percentage,
						'datetime_start'   => $key->datetime_start,
						'datetime_end'     => $key->datetime_end,
					];
				}

				if(!empty($collect)){

					$collect = collect($collect);

					$getProduct = Product::where('active', '=', 1)->get();

					// return $collect;

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
		$product_id       = Input::get('product_id');
		$gross_sell_price = Input::get('gross_sell_price');
		$tax_percentage   = Input::get('tax_percentage');
		$datetime_start   = Input::get('datetime_start');
		$datetime_end     = Input::get('datetime_end');
		$active           = Input::get('active');

		// dd($product_id, $gross_sell_price, $tax_percentage, $datetime_start, $datetime_end, $active, $version);
		DB::transaction(function() use($product_id, $gross_sell_price, $tax_percentage, $datetime_start, $datetime_end, $active){

			foreach ($product_id as $key => $n )
			{
			/*Load array */
				$checkData = ProductSellPrice::where('product_id',$product_id[$key])
					->where('active',1)
					->get();

				$skip = 0;
				foreach ($checkData as $list)
				{
					if($list->datetime_start <= date('YmdHis', strtotime($datetime_start[$key])) && date('YmdHis', strtotime($datetime_start[$key])) <= $list->datetime_end && $active[$key] == 1)
					{
						$skip = 1;
					}
					if($list->datetime_start <= date('YmdHis', strtotime($datetime_end[$key])) && date('YmdHis', strtotime($datetime_end[$key])) <= $list->datetime_end && $active[$key] == 1)
					{
						$skip = 1;
					}
				}

				if(!$skip)
				{
					$arrData = ProductSellPrice::create(array(
						"product_id"          => $product_id[$key],
						"flg_tax"             => $gross_sell_price[$key] <= 0 ? 0 : 1,
						"gross_sell_price"    => $gross_sell_price[$key],
						"tax_percentage"      => $tax_percentage[$key],
						"datetime_start"      => date('YmdHis', strtotime( $datetime_start[$key] )),
						"datetime_end"        => date('YmdHis', strtotime( $datetime_end[$key] )),
						"create_user_id"      => Auth::id(),
						"active"              => $active[$key],
						"active_datetime"     => $active[$key] == 1 ? date('YmdHis') : '00000000000000',
						"non_active_datetime" => $active[$key] == 0 ? date('YmdHis') : '00000000000000',
						"version"             => 0,
						"create_datetime"     => date('YmdHis'),
						"create_user_id"      => Auth::id(),
						"update_datetime"     => '00000000000000',
						"update_user_id"      => -99,
					));
				}
			}
		});

		// dd($arrData);
			// $save = ProductSellPrice::create($arrData);
		// });

		return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully uploaded.');

	}
}
