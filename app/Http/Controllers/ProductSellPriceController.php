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
            'f_date.date'        => 'Invalid filter',
        ];

        $validator = Validator::make($request->all(), [
            'f_provider' => 'integer|nullable',
            'f_active'   => 'nullable|in:Y,N',
            'f_date'     => 'nullable|date',
        ], $message);

        if ($validator->fails()) {
            return redirect()->route('product-sell-price.index');
        }

        return view('product-sell-price.index', compact('request', 'provider'));
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
            'tax_percentage.required_if'     => 'This field is required.',
            'datetime_start.required'        => 'This field is required.',
            // 'datetime_start.after_or_equal' => 'Higher Than Datetime End.',
            // 'datetime_end.required'          => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'product_id'       => 'required',
            'gross_sell_price' => 'required',
            'tax_percentage'   => 'required_if:flg_tax,Y',
            'datetime_start'   => 'required',
            // 'datetime_end'     => 'required',
        ], $message);

        $datetime_end = date('2037-12-01 08:00:00');

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (strtotime('-1 day') >= strtotime($request->datetime_start) && isset($request->active)) {
            return redirect()->back()->with('gagal', 'Datetime Start is Expired.')->withInput();
        }

        $checkData = ProductSellPrice::where('product_id', $request->product_id)
            ->where('active', 'Y')
            ->get();

        $update_id = 0;
        $update    = 0;
        if (!empty($checkData)) {
            foreach ($checkData as $list) {
                if(strtotime($list->datetime_start) >= strtotime($request->datetime_start) && isset($request->active))
                {
                    return redirect()->back()->with('gagal', 'Data is still active.')->withInput();
                }

                if (strtotime($list->datetime_start) < strtotime($request->datetime_start) && strtotime($request->datetime_start) <= strtotime($list->datetime_end) && isset($request->active)) {
                    $update_id = $list->product_sell_price_id;
                    $update    = 1;
                    break;
                }
            }
        }

        if ($update) {
            $index2               = ProductSellPrice::find($update_id);
            $index2->datetime_end = date('YmdHis', strtotime($request->datetime_start . ' -1 second'));
            $index2->update_datetime = date('YmdHis');
            $index2->save();
        }

        $index = new ProductSellPrice;

        $index->product_id       = $request->product_id;
        $index->gross_sell_price = str_replace('.', '', $request->gross_sell_price);
        $index->flg_tax          = isset($request->flg_tax) ? 'Y' : 'N';
        $index->tax_percentage   = isset($request->flg_tax) ? $request->tax_percentage : 0;
        $index->datetime_start   = date('YmdHis', strtotime($request->datetime_start));
        $index->datetime_end     = date('YmdHis', strtotime($datetime_end));

        $index->active = isset($request->active) ? 'Y' : 'N';
        if (isset($request->active)) {
            $index->active_datetime     = date('YmdHis');
            $index->non_active_datetime = '00000000000000';
        } else {
            $index->active_datetime     = '00000000000000';
            $index->non_active_datetime = date('YmdHis');
        }

        $index->version         = 0;
        $index->create_datetime = date('YmdHis');
        $index->create_user_id  = Auth::id();
        $index->update_datetime = '00000000000000';
        $index->update_user_id  = 0;

        $index->save();

        return redirect()->route('product-sell-price.index')->with('berhasil', 'Your data has been successfully saved.');
    }

    public function ubah($id)
    {
        $index = ProductSellPrice::find($id);

        if (!$index) {
            return redirect()->route('product-sell-price.index')->with('gagal', 'Data not exist.');
        }

        $product = Product::get();

        return view('product-sell-price.ubah', compact('index', 'product'));
    }

    //not used
    public function update(Request $request)
    {
        $message = [
            'product_id.required'            => 'This field is required.',
            'gross_sell_price.required'      => 'This field is required.',
            'tax_percentage.required_if'     => 'This field is required.',
            'tax_percentage.numeric'         => 'Numeric Only.',
            'datetime_start.required'        => 'This field is required.',
            'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
            'datetime_end.required'          => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'product_id'       => 'required',
            'gross_sell_price' => 'required',
            'tax_percentage'   => 'required_if:flg_tax,Y|numeric',
            'datetime_start'   => 'required|before_or_equal:datetime_end',
            'datetime_end'     => 'required',
        ], $message);

        if ($validator->fails()) {
            return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->withErrors($validator)->withInput();
        }

        $index = ProductSellPrice::find($request->product_sell_price_id);

        if (!$index) {
            return redirect()->route('product-sell-price.index')->with('gagal', 'Data not exist.');
        }

        if (strtotime('-1 day') >= strtotime($request->datetime_start) && isset($request->active)) {
            return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Datetime Start is Expired.')->withInput();
        }

        $checkData = ProductSellPrice::where('product_id', $request->product_id)
            ->where('active', 'Y')
            ->where('product_sell_price_id', '<>', $request->product_sell_price_id)
            ->get();

        $update_id = 0;
        $update    = 0;
        if (!empty($checkData)) {
            foreach ($checkData as $list) {
                if(strtotime($list->datetime_start) >= strtotime($request->datetime_start) && isset($request->active))
                {
                    return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Data is still active.')->withInput();
                }

                if (strtotime($list->datetime_start) < strtotime($request->datetime_start) && strtotime($request->datetime_start) <= strtotime($list->datetime_end) && isset($request->active)) {
                    $update_id = $list->product_sell_price_id;
                    $update    = 1;
                    break;
                }
            }
        }

        if ($index->version != $request->version) {
            return redirect()->route('product-sell-price.ubah', ['id' => $request->product_sell_price_id])->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        if ($update) {
            $index2               = ProductSellPrice::find($update_id);
            $index2->datetime_end = date('YmdHis', strtotime($request->datetime_start . ' -1 second'));
            $index2->save();
        }

        $index->product_id       = $request->product_id;
        $index->gross_sell_price = str_replace('.', '', $request->gross_sell_price);
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

        if (!$index) {
            return redirect()->back()->with('gagal', 'Data not exist.');
        }

        if (strtotime('-1 day') >= strtotime($index->datetime_start) && $index->active != 'Y') {
            return redirect()->back()->with('gagal', 'Datetime Start is Expired.')->withInput();
        }

        $checkData = ProductSellPrice::where('product_id', $index->product_id)
            ->where('active', 'Y')
            ->get();

        $update_id = 0;
        $update    = 0;
        if (!empty($checkData)) {
            foreach ($checkData as $list) {
                if(strtotime($list->datetime_start) >= strtotime($index->datetime_start) && $index->active != 'Y')
                {
                    return redirect()->back()->with('gagal', 'Data is still active.');
                }

                if (strtotime($list->datetime_start) < strtotime($index->datetime_start) && strtotime($index->datetime_start) <= strtotime($list->datetime_end) && $index->active != 'Y') {
                    $update_id = $list->product_sell_price_id;
                    $update    = 1;
                    break;
                }
            }
        }

        if ($index->version != $request->version) {
            return redirect()->back()->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        if (date('YmdHis', strtotime($index->datetime_end)) < date('YmdHis') && $index->active != 'Y') {
            return redirect()->back()->with('gagal', 'Data is outdate, can\'t to active again.');
        }

        if ($update) {
            $index2               = ProductSellPrice::find($update_id);
            $index2->datetime_end = date('YmdHis', strtotime($index->datetime_start . ' -1 second'));
            $index2->save();
        }

        if ($index->active == 'Y') {

            $index->active              = 'N';
            $index->non_active_datetime = date('YmdHis');

            $index->version += 1;
            $index->update_datetime = date('YmdHis');
            $index->update_user_id  = Auth::id();

            $index->save();

            return redirect()->back()->with('berhasil', 'Successfully Nonactive');
        } else {

            $index->active          = 'Y';
            $index->active_datetime = date('YmdHis');

            $index->version += 1;
            $index->update_datetime = date('YmdHis');
            $index->update_user_id  = Auth::id();

            $index->save();

            return redirect()->back()->with('berhasil', 'Successfully Activated ');
        }
    }

    public function delete($id, Request $request)
    {
        $index = ProductSellPrice::find($id);

        if (!$index) {
            return redirect()->back()->with('gagal', 'Data not exist.');
        }

        if ($index->version != $request->version) {
            return redirect()->back()->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        $index->delete();

        return redirect()->back()->with('berhasil', 'Successfully Deleted ');
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
        if ($request->f_date != null) {
            $f_date = date('YmdHis', strtotime($request->f_date . ' 23:59:59'));
            $getDatas->where('datetime_start', '<=', $f_date)
                ->where('datetime_end', '>=', $f_date);
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
                if ($getData->flg_tax == 'Y') {
                    return "Y";
                } else {
                    return "N";
                }
            })
            ->editColumn('tax_percentage', function ($getData) {
                if ($getData->flg_tax == 'Y') {
                    return $getData->tax_percentage . "%";
                } else {
                    return "0%";
                }
            })
            ->editColumn('datetime_start', function ($getData) {
                return date('Y-m-d H:i:s', strtotime($getData->datetime_start));
            })
            ->editColumn('datetime_end', function ($getData) {
                return date('Y-m-d H:i:s', strtotime($getData->datetime_end));
            })
            ->addColumn('action', function ($getData) {
                $actionHtml = '';
                /*if (Auth::user()->can('update-product-sell-price')) {
                    $actionHtml = $actionHtml . "
                        <a href='" . route('product-sell-price.ubah', $getData->product_sell_price_id) . "'' class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></a>";
                }*/
                if (Auth::user()->can('create-product-sell-price')) {
                    $actionHtml = $actionHtml . "
                        <a href='" . route('product-sell-price.addWithData', $getData->product_sell_price_id) . "'' class='btn btn-xs btn-success btn-sm' data-toggle='tooltip' data-placement='top' title='Add ".$getData->product_name."'><i class='fa fa-plus'></i></a>";
                }
                if (Auth::user()->can('activate-product-sell-price')) {
                    if ($getData->active == 'Y') {
                        $actionHtml = $actionHtml . "<a href='' class='unpublish' data-value='" . $getData->product_sell_price_id . "' data-version='" . $getData->version . "' data-toggle='modal' data-target='.modal-nonactive'><span class='btn btn-dark btn-xs' data-toggle='tooltip' data-placement='top' title='Non Active'><i class='fa fa-times'></i></span></a>";
                    } else {
                        $actionHtml = $actionHtml . "<a href='' class='publish' data-value='" . $getData->product_sell_price_id . "' data-version='" . $getData->version . "' data-toggle='modal' data-target='.modal-active'><span class='btn btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Active'><i class='fa fa-check'></i></span></a>";
                    }

                }
                if (Auth::user()->can('delete-product-sell-price')) {
                    $actionHtml = $actionHtml . "<a href='' class='delete' data-value='" . $getData->product_sell_price_id . "' data-version='" . $getData->version . "' data-toggle='modal' data-target='.modal-delete'>
                            <span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></span>
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
                $sheet->row(1, array('product_code', 'gross_sell_price', 'tax_percentage', 'datetime_start', 'active'));
                $sheet->setColumnFormat(array(
                    'A' => '',
                    'B' => '0.00',
                    'C' => '0.00',
                    'D' => 'YYYY-MM-DD HH:mm:ss',
                    'E' => '',
                ));
            });

            $excel->sheet('product_id', function ($sheet) use ($getProduct) {
                $sheet->fromArray($getProduct, null, 'A6', true);
                $sheet->row(1, array('Example'));
                $sheet->mergeCells('A1:E1');
                $sheet->row(2, array('product_code', 'gross_sell_price', 'tax_percentage', 'datetime_start', 'active'));
                $sheet->row(3, array('PRO1', '45000', '10', '2017-07-01 12:00:00', 'Y'));
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
                }
            } else {
                return view('product-sell-price.masal')->with('gagal', 'Please Download Template');
            }
        } else {
            return view('product-sell-price.masal')->with('gagal', 'Please Select Template');
        }

        $datetime_end     = '2037-12-31 23:59:59';

        $time_commit = strtotime('-1 day');
        $row = 1;

        foreach ($collect as $list) {
            /*Load array */

            $skip    = 0;
            $update  = 0;
            $product = Product::where('product_code', strtoupper($list['product_code']))->first();

            if ($product) {
                $checkData = ProductSellPrice::where('product_id', $product->product_id)
                    ->where('active', 'Y')
                    ->get();
            } else {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Data Product not found</span></h4>';
                }
                $skip = 1;
            }

            if ($list['gross_sell_price'] == '') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Gross Sell Price is empty</span></h4>';
                }
                $skip = 1;
            }

            if ($list['tax_percentage'] == '') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Tax Percentage is empty</span></h4>';
                }
                $skip = 1;
            }

            if ($list['datetime_start'] == '') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Datetime Start is empty</span></h4>';
                }
                $skip = 1;
            }

            if (date('YmdHis', strtotime($list['datetime_start'])) > date('YmdHis', strtotime($datetime_end))) {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Datetime start is bigger than Datetime end</span></h4>';
                }
                $skip = 1;
            }


            if ($time_commit >= strtotime($list['datetime_start']) && strtoupper($list['active']) == 'Y') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Datetime Start is Expired</span></h4>';
                }
                $skip = 1;
            }

            if (!empty($checkData)) {
                foreach ($checkData as $list2) {

                    if (strtotime($list2->datetime_start) < strtotime($list['datetime_start']) && strtotime($list['datetime_start']) <= strtotime($list2->datetime_end) && strtoupper($list['active']) == 'Y') {
                        $update_id = $list2->product_sell_price_id;
                        $update    = 1;
                    }

                    if(strtotime($list2->datetime_start) >= strtotime($list['datetime_start']) && strtoupper($list['active']) == 'Y')
                    {
                        if (!$skip) {
                            $message = '<h4><span class="label label-danger">Data is still active</span></h4>';
                        }
                        $skip = 1;
                    }
                }
            }

            if ($update && !$skip) {
                $index               = ProductSellPrice::find($update_id);
                $index->datetime_end = date('YmdHis', strtotime($list['datetime_start'] . ' -1 second'));
                $index->save();
            }

            if (!$skip) {
                ProductSellPrice::insert(
                    [
                        "product_id"          => $product->product_id,
                        "gross_sell_price"    => $list['gross_sell_price'],
                        "flg_tax"             => $list['tax_percentage'] > 0 ? 'Y' : 'N',
                        "tax_percentage"      => $list['tax_percentage'],
                        "datetime_start"      => date('YmdHis', strtotime($list['datetime_start'])),
                        "datetime_end"        => date('YmdHis', strtotime($datetime_end)),
                        "create_user_id"      => Auth::id(),
                        "active"              => strtoupper($list['active']),
                        "active_datetime"     => strtoupper($list['active']) == 'Y' ? date('YmdHis') : '00000000000000',
                        "non_active_datetime" => strtoupper($list['active']) != 'Y' ? date('YmdHis') : '00000000000000',
                        "version"             => 0,
                        "create_datetime"     => date('YmdHis'),
                        "create_user_id"      => Auth::id(),
                        "update_datetime"     => '00000000000000',
                        "update_user_id"      => 0,
                    ]);

                $pass[] = [
                    'row'              => $row++,
                    'product_code'     => $list['product_code'],
                    'gross_sell_price' => $list['gross_sell_price'],
                    'tax_percentage'   => $list['tax_percentage'],
                    'datetime_start'   => $list['datetime_start'],
                    'datetime_end'     => $datetime_end,
                    'active'           => $list['active'],
                ];
            } else {
                $error[] = [
                    'row'              => $row++,
                    'product_code'     => $list['product_code'],
                    'gross_sell_price' => $list['gross_sell_price'],
                    'tax_percentage'   => $list['tax_percentage'],
                    'datetime_start'   => $list['datetime_start'],
                    'datetime_end'     => $datetime_end,
                    'active'           => $list['active'],
                    'message'          => $message,
                ];
            }
        }

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
