<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSellPrice;
use App\Models\Provider;
use Auth;
use Excel;
use Illuminate\Http\Request;
use Input;
use Validator;
use Yajra\Datatables\Facades\Datatables;

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
            'f_active.in'        => 'Invalid filter',
        ];

        $validator = Validator::make($request->all(), [
            'f_provider' => 'integer|nullable',
            'f_active'   => 'nullable|in:Y,N',
        ], $message);

        if ($validator->fails()) {
            return redirect()->route('product-sell-price.index');
        }

        $index = ProductSellPrice::join('sw_product', 'sw_product.product_id', '=', 'sw_product_sell_price.product_id')
            ->select('sw_product_sell_price.*');

        if (isset($request->f_provider) && $request->f_provider != '') {
            $index->where('sw_product.provider_id', $request->f_provider);
        }

        if (isset($request->f_active) && $request->f_active != '') {
            $index->where('sw_product_sell_price.active', $request->f_active);
        }

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
            'product_id.required'            => 'This field is required.',
            'gross_sell_price.required'      => 'This field is required.',
            'gross_sell_price.numeric'       => 'Numeric Only.',
            'tax_percentage.required_if'     => 'This field is required.',
            'datetime_start.required'        => 'This field is required.',
            'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
            'datetime_end.required'          => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'product_id'       => 'required',
            'gross_sell_price' => 'required|numeric',
            'tax_percentage'   => 'required_if:flg_tax,Y',
            'datetime_start'   => 'required|before_or_equal:datetime_end',
            'datetime_end'     => 'required',
        ], $message);

        if ($validator->fails()) {
            return redirect()->route('product-sell-price.tambah')->withErrors($validator)->withInput();
        }

        $checkData = ProductSellPrice::where('product_id', $request->product_id)
            ->where('active', 'Y')
            ->get();

        foreach ($checkData as $list) {
            if ($list->datetime_start <= date('YmdHis', strtotime($request->datetime_start)) && date('YmdHis', strtotime($request->datetime_start)) <= $list->datetime_end && isset($request->active)) {
                return redirect()->route('product-sell-price.tambah')->with('gagal', 'Data is still active.')->withInput();
            }
            if ($list->datetime_start <= date('YmdHis', strtotime($request->datetime_end)) && date('YmdHis', strtotime($request->datetime_end)) <= $list->datetime_end && isset($request->active)) {
                return redirect()->route('product-sell-price.tambah')->with('gagal', 'Data is still active.')->withInput();
            }
            if (date('YmdHis', strtotime($request->datetime_start)) <= $list->datetime_start && $list->datetime_end <= date('YmdHis', strtotime($request->datetime_end)) && isset($request->active)) {
                return redirect()->route('product-sell-price.tambah')->with('gagal', 'Data is still active.')->withInput();
            }
        }

        $index = new ProductSellPrice;

        $index->product_id       = $request->product_id;
        $index->gross_sell_price = $request->gross_sell_price;
        $index->flg_tax          = isset($request->flg_tax) ? 'Y' : 'N';
        $index->tax_percentage   = isset($request->flg_tax) ? $request->tax_percentage : 0;
        $index->datetime_start   = date('YmdHis', strtotime($request->datetime_start));
        $index->datetime_end     = date('YmdHis', strtotime($request->datetime_end));

        $index->active = isset($request->active) ? 'Y' : 'N';
        if (isset($request->active)) {
            $index->active_datetime     = date('YmdHis');
            $index->non_active_datetime = 00000000000000;
        } else {
            $index->active_datetime     = 00000000000000;
            $index->non_active_datetime = date('YmdHis');
        }

        $index->version         = 0;
        $index->create_datetime = date('YmdHis');
        $index->create_user_id  = Auth::id();
        $index->update_datetime = 00000000000000;
        $index->update_user_id  = 0;

        $index->save();

        return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully saved.');
    }

    public function ubah($id)
    {
        $index = ProductSellPrice::find($id);

        $product = Product::get();

        return view('product-sell-price.ubah', compact('index', 'product'));
    }

    public function update(Request $request)
    {
        $message = [
            'product_id.required'            => 'This field is required.',
            'gross_sell_price.required'      => 'This field is required.',
            'gross_sell_price.numeric'       => 'Numeric Only.',
            'tax_percentage.required_if'     => 'This field is required.',
            'datetime_start.required'        => 'This field is required.',
            'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
            'datetime_end.required'          => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'product_id'       => 'required',
            'gross_sell_price' => 'required|numeric',
            'tax_percentage'   => 'required_if:flg_tax,Y',
            'datetime_start'   => 'required|before_or_equal:datetime_end',
            'datetime_end'     => 'required',
        ], $message);

        if ($validator->fails()) {
            return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->withErrors($validator)->withInput();
        }

        $index = ProductSellPrice::find($request->product_sell_price_id);

        $checkData = ProductSellPrice::where('product_id', $request->product_id)
            ->where('active', 'Y')
            ->where('product_sell_price_id', '<>', $request->product_sell_price_id)
            ->get();

        foreach ($checkData as $list) {
            if ($list->datetime_start <= date('YmdHis', strtotime($request->datetime_start)) && date('YmdHis', strtotime($request->datetime_start)) <= $list->datetime_end && isset($request->active));
            {
                return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Data is still active.')->withInput();
            }
            if ($list->datetime_start <= date('YmdHis', strtotime($request->datetime_end)) && date('YmdHis', strtotime($request->datetime_end)) <= $list->datetime_end && isset($request->active));
            {
                return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Data is still active.')->withInput();
            }
            if (date('YmdHis', strtotime($request->datetime_start)) <= $list->datetime_start && $list->datetime_end <= date('YmdHis', strtotime($request->datetime_end)) && isset($request->active)) {
                return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Data is still active.')->withInput();
            }
        }

        if ($index->version != $request->version) {
            return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        $index->product_id       = $request->product_id;
        $index->gross_sell_price = $request->gross_sell_price;
        $index->flg_tax          = isset($request->flg_tax) ? 'Y' : 'N';
        $index->tax_percentage   = isset($request->flg_tax) ? $request->tax_percentage : 0;
        $index->datetime_start   = date('YmdHis', strtotime($request->datetime_start));
        $index->datetime_end     = date('YmdHis', strtotime($request->datetime_end));

        $index->active = isset($request->active) ? 'Y' : 'N';
        if (isset($request->active)) {
            $index->active_datetime = date('YmdHis');
        } else {
            $index->non_active_datetime = date('YmdHis');
        }

        $index->version += 1;
        $index->update_datetime = date('YmdHis');
        $index->update_user_id  = Auth::id();

        $index->save();

        return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully updated.');

    }

    public function active($id, Request $request)
    {
        $index = ProductSellPrice::find($id);

        $checkData = ProductSellPrice::where('product_id', $index->product_id)
            ->where('active', 'Y')
            ->get();

        foreach ($checkData as $list) {
            if ($list->datetime_start <= date('YmdHis', strtotime($index->datetime_start)) && date('YmdHis', strtotime($index->datetime_start)) <= $list->datetime_end && !$index->active) {
                return redirect()->route('product-sell-price.index')->with('gagal', 'Data is still active.')->withInput();
            }
            if ($list->datetime_start <= date('YmdHis', strtotime($index->datetime_end)) && date('YmdHis', strtotime($index->datetime_end)) <= $list->datetime_end && !$index->active) {
                return redirect()->route('product-sell-price.index')->with('gagal', 'Data is still active.')->withInput();
            }
        }

        if ($index->version != $request->version) {
            return redirect()->route('product.index')->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        if ($index->active) {

            $index->active              = 0;
            $index->non_active_datetime = date('YmdHis');

            $index->version += 1;
            $index->update_datetime = date('YmdHis');
            $index->update_user_id  = Auth::id();

            $index->save();

            return redirect()->route('product-sell-price.index')->with('berhasil', 'Successfully Nonactive');
        } else {

            $index->active          = 1;
            $index->active_datetime = date('YmdHis');

            $index->version += 1;
            $index->update_datetime = date('YmdHis');
            $index->update_user_id  = Auth::id();

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

    public function yajraGetData(Request $request)
    {

        $f_provider = $request->query('f_provider');
        $f_active   = $request->query('f_active');

        $getDatas = ProductSellPrice::leftJoin('sw_product', 'sw_product.product_id', 'sw_product_sell_price.product_id')
            ->select([
                'sw_product.product_name as product_name',
                'sw_product.nominal as nominal',
                'product_sell_price_id',
                'gross_sell_price',
                'flg_tax',
                'tax_percentage',
                'datetime_start',
                'datetime_end',
                'sw_product_sell_price.active',
                'sw_product_sell_price.version',
            ]);

        if ($f_provider != null) {
            $getDatas->where('sw_product.provider_id', $f_provider);
        }
        if ($f_active != null) {
            $getDatas->where('sw_product_sell_price.active', $f_active);
        }
        $getDatas = $getDatas->get();

        $start      = 1;
        $Datatables = Datatables::of($getDatas)
            ->addColumn('slno', function ($getData) use (&$start) {
                return $start++;
            })
            ->editColumn('product_name', function ($getData) {
                return $getData->product_name . ' - Rp. ' . number_format($getData->nominal, 2);
            })
            ->editColumn('gross_sell_price', function ($getData) {
                return 'Rp. ' . number_format($getData->gross_sell_price, 2);
            })
            ->editColumn('flg_tax', function ($getData) {
                if ($getData->flg_tax == 1) {
                    return "Y";
                } else if ($getData->flg_tax == 0) {
                    return "N";
                }
            })
            ->editColumn('tax_percentage', function ($getData) {
                if ($getData->flg_tax == 1) {
                    return $getData->tax_percentage . "%";
                } else if ($getData->flg_tax == 0) {
                    return "0%";
                }
            })
            ->editColumn('datetime_start', function ($getData) {
                return date('d-m-Y H:i:s', strtotime($getData->datetime_start));
            })
            ->editColumn('datetime_end', function ($getData) {
                return date('d-m-Y H:i:s', strtotime($getData->datetime_end));
            })
            ->addColumn('action', function ($getData) {
                $actionHtml = '';
                if (Auth::user()->can('update-product-sell-price')) {
                    $actionHtml = $actionHtml . "
						<a
							href='" . route('product-sell-price.ubah', $getData->product_sell_price_id) . "''
							class='btn btn-xs btn-warning btn-sm'
							data-toggle='tooltip'
							data-placement='top'
							title='Ubah'
						><i class='fa fa-pencil'></i></a>";
                }
                if (Auth::user()->can('delete-product-sell-price')) {
                    $actionHtml = $actionHtml . "
						<a
							href=''
							class='delete'
							data-value='" . $getData->product_sell_price_id . "'
							data-version='" . $getData->version . "'
							data-toggle='modal'
							data-target='.modal-delete'
						>
							<span
								class='btn btn-xs btn-danger btn-sm'
								data-toggle='tooltip'
								data-placement='top'
								title='Hapus'
							><i class='fa fa-remove'></i></span>
						</a>";
                }
                return $actionHtml;
            });

        if (Auth::user()->can('activate-product-sell-price')) {
            $Datatables = $Datatables->editColumn('active', function ($getData) {
                if ($getData->active == 'Y') {
                    return "
						<a
							href=''
							class='unpublish'
							data-value='" . $getData->product_sell_price_id . "'
							data-version='" . $getData->version . "'
							data-toggle='modal'
							data-target='.modal-nonactive'
						>
							<span
								class='label label-success'
								data-toggle='tooltip'
								data-placement='top'
								title='Active'
							>Active</span>
						</a><br>";
                } else {
                    return "
						<a
							href=''
							class='publish'
							data-value='" . $getData->product_sell_price_id . "'
							data-version='" . $getData->version . "'
							data-toggle='modal'
							data-target='.modal-active'
						>
							<span
								class='label label-danger'
								data-toggle='tooltip'
								data-placement='top'
								title='Non Active'
							>Non Active</span>
						</a><br>";
                }
            });
        }

        $Datatables = $Datatables
            ->escapeColumns(['*'])
            ->make(true);

        return $Datatables;
    }

    public function upload()
    {
        return view('product-sell-price.masal');
    }

    public function template()
    {
        $getProduct = Product::join('sw_provider', 'sw_provider.provider_id', '=', 'sw_product.provider_id')
            ->select('sw_product.product_code', 'sw_product.product_name', 'sw_product.nominal', 'sw_provider.provider_name', 'sw_product.active')
            ->orderBy('sw_provider.provider_name', 'asc')
            ->get()
            ->toArray();

        return Excel::create('Template Product Sell Price Import', function ($excel) use ($getProduct) {
            $excel->sheet('Data-Import', function ($sheet) {
                $sheet->row(1, array('product_code', 'gross_sell_price', 'tax_percentage', 'datetime_start', 'datetime_end', 'active'));
                $sheet->setColumnFormat(array(
                    'A' => '',
                    'B' => '0.00',
                    'C' => '0.00',
                    'D' => 'YYYY-MM-DD HH:mm:ss',
                    'E' => 'YYYY-MM-DD HH:mm:ss',
                    'F' => '',
                ));
            });

            $excel->sheet('product_id', function ($sheet) use ($getProduct) {
                $sheet->fromArray($getProduct, null, 'A6', true);
                $sheet->row(1, array('Example'));
                $sheet->mergeCells('A1:E1');
                $sheet->row(2, array('product_code', 'gross_sell_price', 'tax_percentage', 'datetime_start', 'datetime_end', 'active'));
                $sheet->row(3, array('PRO1', '45000', '10', '2017-07-01 12:00:00', '2017-07-31 12:00:00', 'Y'));
                $sheet->row(5, array('Data Product'));
                $sheet->mergeCells('A5:C5');
                $sheet->row(6, array('product_code', 'product_name', 'nominal', 'provider_name', 'active'));
                $sheet->setAllBorders('thin');
                $sheet->setFreeze('A7');

                $sheet->cells('A2:F3', function ($cells) {
                    $cells->setBackground('#5c92e8');
                    $cells->setFontColor('#000000');
                    $cells->setFontWeight('bold');
                });

                $sheet->cells('A6:E6', function ($cells) {
                    $cells->setBackground('#000000');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontWeight('bold');
                });

            });
        })->download('xls');
    }

    public function prosesTemplate(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = Input::file('file')->getRealPath();
            $data = Excel::selectSheets('Data-Import')->load($path, function ($reader) {
            })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data as $key) {
                    $collect[] = [
                        'product_code'     => $key->product_code,
                        'gross_sell_price' => $key->gross_sell_price,
                        'tax_percentage'   => $key->tax_percentage,
                        'datetime_start'   => $key->datetime_start,
                        'datetime_end'     => $key->datetime_end,
                        'active'           => $key->active,
                    ];
                }

                if (!empty($collect)) {

                    $collect = collect($collect);

                    return view('product-sell-price.masal', compact('collect'));
                }
            } else {
                return view('product-sell-price.masal')->with('gagal', 'Please Download Template');
            }
        } else {
            return view('product-sell-price.masal')->with('gagal', 'Please Select Template');
        }
    }

    public function storeTemplate(Request $request)
    {
        $product_code     = $request->product_code;
        $gross_sell_price = $request->gross_sell_price;
        $tax_percentage   = $request->tax_percentage;
        $datetime_start   = $request->datetime_start;
        $datetime_end     = $request->datetime_end;
        $active           = $request->active;

        // DB::transaction(function () use ($product_code, $gross_sell_price, $tax_percentage, $datetime_start, $datetime_end, $active) {

        foreach ($product_code as $key => $n) {
            /*Load array */
            $product = Product::where('product_code', strtoupper($product_code[$key]))->first();

            $skip = 0;
            if ($product) {
                $checkData = ProductSellPrice::where('product_id', $product->product_id)
                    ->where('active', 'Y')
                    ->get();
            } else {
                if (!$skip) {
                    $message = 'Data Product not found';
                }
                $skip = 1;
            }

            if ($gross_sell_price[$key] == '') {
                if (!$skip) {
                    $message = 'Gross Sell Price is empty';
                }
                $skip = 1;
            }

            if ($tax_percentage[$key] == '') {
                if (!$skip) {
                    $message = 'Tax Percentage is empty';
                }
                $skip = 1;
            }

            if ($datetime_start[$key] == '') {
                if (!$skip) {
                    $message = 'Datetime Start is empty';
                }
                $skip = 1;
            }

            if ($datetime_end[$key] == '') {
                if (!$skip) {
                    $message = 'Datetime end is empty';
                }
                $skip = 1;
            }

            if (date('YmdHis', strtotime($datetime_start[$key])) > date('YmdHis', strtotime($datetime_end[$key]))) {
                if (!$skip) {
                    $message = 'Datetime start is bigger than Datetime end';
                }
                $skip = 1;
            }

            foreach ($checkData as $list) {
                if ($list->datetime_start <= date('YmdHis', strtotime($datetime_start[$key])) && date('YmdHis', strtotime($datetime_start[$key])) <= $list->datetime_end && $active[$key] == 'Y') {
                    if (!$skip) {
                        $message = 'Data still active';
                    }
                    $skip = 1;
                }
                if ($list->datetime_start <= date('YmdHis', strtotime($datetime_end[$key])) && date('YmdHis', strtotime($datetime_end[$key])) <= $list->datetime_end && $active[$key] == 'Y') {
                    if (!$skip) {
                        $message = 'Data still active';
                    }
                    $skip = 1;
                }
                if (date('YmdHis', strtotime($datetime_start[$key])) <= $list->datetime_start && $list->datetime_end <= date('YmdHis', strtotime($datetime_end[$key])) && $active[$key] == 'Y') {
                    if (!$skip) {
                        $message = 'Data still active';
                    }
                    $skip = 1;
                }
            }

            if (!$skip) {
                ProductSellPrice::insert(
                [
                    "product_id"          => $product->product_id,
                    "gross_sell_price"    => $gross_sell_price[$key],
                    "flg_tax"             => $tax_percentage[$key] > 0 ? 'Y' : 'N',
                    "tax_percentage"      => $tax_percentage[$key],
                    "datetime_start"      => date('YmdHis', strtotime($datetime_start[$key])),
                    "datetime_end"        => date('YmdHis', strtotime($datetime_end[$key])),
                    "create_user_id"      => Auth::id(),
                    "active"              => $active[$key],
                    "active_datetime"     => $active[$key] == 'Y' ? date('YmdHis') : '00000000000000',
                    "non_active_datetime" => $active[$key] != 'Y' ? date('YmdHis') : '00000000000000',
                    "version"             => 0,
                    "create_datetime"     => date('YmdHis'),
                    "create_user_id"      => Auth::id(),
                    "update_datetime"     => '00000000000000',
                    "update_user_id"      => 0,
                ]);

                $pass[] = [
                    'row'              => $key,
                    'product_code'     => $product_code[$key],
                    'gross_sell_price' => $gross_sell_price[$key],
                    'tax_percentage'   => $tax_percentage[$key],
                    'datetime_start'   => $datetime_start[$key],
                    'datetime_end'     => $datetime_end[$key],
                    'active'           => $active[$key],
                ];
            } else {
                $error[] = [
                    'row'              => $key,
                    'product_code'     => $product_code[$key],
                    'gross_sell_price' => $gross_sell_price[$key],
                    'tax_percentage'   => $tax_percentage[$key],
                    'datetime_start'   => $datetime_start[$key],
                    'datetime_end'     => $datetime_end[$key],
                    'active'           => $active[$key],
                    'message'          => $message,
                ];
            }
        }
        // });

        if (!empty($error)) {

            $error = collect($error);

            if (!empty($pass)) {
                $pass = collect($pass);
            } else {
                $pass = '';
            }

            return view('product-sell-price.masal', compact('error', 'pass'));
        }

        return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully uploaded.')->with(compact('error'));

    }
}
