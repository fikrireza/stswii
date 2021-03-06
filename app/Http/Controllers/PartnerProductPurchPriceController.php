<?php

namespace App\Http\Controllers;

use App\Models\PartnerProduct;
use App\Models\PartnerProductPurchPrice;
use App\Models\PartnerPulsa;
use App\Models\Provider;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use Validator;
use Yajra\Datatables\Facades\Datatables;

class PartnerProductPurchPriceController extends Controller
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
        $partner  = PartnerPulsa::get();

        $message = [
            'f_provider.integer' => 'Invalid filter',
            'f_partner.integer'  => 'Invalid filter',
            'f_active.in'        => 'Invalid filter',
            'f_date.date'        => 'Invalid filter',
        ];

        $validator = Validator::make($request->all(), [
            'f_provider' => 'integer|nullable',
            'f_partner'  => 'integer|nullable',
            'f_active'   => 'nullable|in:Y,N',
            'f_date'   => 'nullable|date',
        ], $message);

        if ($validator->fails()) {
            return redirect()->route('partner-product-purch-price.index');
        }

        return view('partner-product-purchase-price.index', compact('request','provider','partner'));
    }

    public function tambah()
    {
        $partnerProduct = PartnerProduct::where('active', 'Y')->orderBy('partner_pulsa_id', 'ASC')->get();

        return view('partner-product-purchase-price.tambah', compact('partnerProduct'));
    }

    public function store(Request $request)
    {
        $message = [
            'partner_product_id.required'    => 'This field is required.',
            'gross_purch_price.required'     => 'This field is required.',
            //'tax_percentage.required_if'     => 'This field is required.',
            'datetime_start.required'        => 'This field is required.',
            // 'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
            // 'datetime_end.required'          => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'partner_product_id' => 'required',
            'gross_purch_price'  => 'required',
            //'tax_percentage'     => 'required_if:flg_tax,Y',
            'datetime_start'     => 'required',
            // 'datetime_end'       => 'required',
        ], $message);

        $datetime_end = date('2037-12-01 08:00:00');

        if ($validator->fails()) {
            return redirect()->route('partner-product-purch-price.tambah')->withErrors($validator)->withInput();
        }

        if (strtotime('-1 day') >= strtotime($request->datetime_start) && isset($request->active)) {
            return redirect()->route('partner-product-purch-price.tambah')->with('gagal', 'Datetime Start is Expired.')->withInput();
        }

        $checkData = PartnerProductPurchPrice::where('partner_product_id', $request->partner_product_id)
            ->where('active', 'Y')
            ->get();

        $partnerPulsa = PartnerPulsa::join('sw_partner_product', 'sw_partner_product.partner_pulsa_id', '=', 'sw_partner_pulsa.partner_pulsa_id')
            ->select('sw_partner_pulsa.flg_pkp')
            ->where('sw_partner_product.partner_product_id', $request->partner_product_id)
            ->first();

        $update_id = 0;
        $update    = 0;
        if(!empty($checkData))
        {
            foreach ($checkData as $list) {
                if (strtotime($list->datetime_start) <= strtotime($request->datetime_start) && strtotime($request->datetime_start) <= strtotime($list->datetime_end) && isset($request->active)) {
                    $update_id = $list->partner_product_purch_price_id;
                    $update    = 1;
                    break;
                }
            }
        }

        if($update)
        {
            $index2 = PartnerProductPurchPrice::find($update_id);
            $index2->datetime_end = date('YmdHis', strtotime($request->datetime_start.' -1 second'));
            $index2->save();
        }

        $index = new PartnerProductPurchPrice;

        $index->partner_product_id = $request->partner_product_id;
        $index->gross_purch_price  = str_replace('.', '',$request->gross_purch_price);
        $index->flg_tax            = $partnerPulsa->flg_pkp;
        $index->tax_percentage     = $partnerPulsa->flg_pkp == 'Y' ? 10 : 0;
        $index->datetime_start     = date('YmdHis', strtotime($request->datetime_start));
        $index->datetime_end       = date('YmdHis', strtotime($datetime_end));

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

        return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Your data has been successfully saved.');
    }

    public function edit($id)
    {
        $index = PartnerProductPurchPrice::find($id);

        if (!$index) {
            return redirect()->route('partner-product-purch-price.index')->with('gagal', 'Data Not Found');
        }

        $partnerProduct = PartnerProduct::where('active', 'Y')->orderBy('partner_pulsa_id', 'ASC')->get();

        return view('partner-product-purchase-price.ubah', compact('index', 'partnerProduct'));
    }

    public function update(Request $request)
    {
        $message = [
            'partner_product_id.required'    => 'This field is required.',
            'gross_purch_price.required'     => 'This field is required.',
            //'tax_percentage.required_if'     => 'This field is required.',
            //'tax_percentage.numeric'         => 'Numeric Only.',
            'datetime_start.required'        => 'This field is required.',
            'datetime_start.before_or_equal' => 'Higher Than Datetime End.',
            'datetime_end.required'          => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'partner_product_id' => 'required',
            'gross_purch_price'  => 'required',
            //'tax_percentage'     => 'required_if:flg_tax,Y|numeric',
            'datetime_start'     => 'required|before_or_equal:datetime_end',
            'datetime_end'       => 'required',
        ], $message);

        if ($validator->fails()) {
            return redirect()->route('partner-product-purch-price.edit', ['id' => $request->partner_product_purch_price_id])->withErrors($validator)->withInput();
        }

        $index = PartnerProductPurchPrice::find($request->partner_product_purch_price_id);

        if (strtotime('-1 day') >= strtotime($request->datetime_start) && isset($request->active)) {
            return redirect()->route('partner-product-purch-price.edit', ['id' => $request->partner_product_purch_price_id])->with('gagal', 'Datetime Start is Expired.')->withInput();
        }

        $checkData = PartnerProductPurchPrice::where('partner_product_id', $request->partner_product_id)
            ->where('active', 'Y')
            ->where('partner_product_purch_price_id', '<>', $request->partner_product_purch_price_id)
            ->get();

        $partnerPulsa = PartnerPulsa::join('sw_partner_product', 'sw_partner_product.partner_pulsa_id', '=', 'sw_partner_pulsa.partner_pulsa_id')
            ->select('sw_partner_pulsa.flg_pkp')
            ->where('sw_partner_product.partner_product_id', $request->partner_product_id)
            ->first();

        $update_id = 0;
        $update    = 0;
        if(!empty($checkData))
        {
            foreach ($checkData as $list) {

                if (strtotime($list->datetime_start) <= strtotime($request->datetime_start) && strtotime($request->datetime_start) <= strtotime($list->datetime_end) && isset($request->active)) {
                    $update_id = $list->partner_product_purch_price_id;
                    $update    = 1;
                    break;
                }
            }
        }

        if ($index->version != $request->version) {
            return redirect()->route('partner-product-purch-price.edit', ['id' => $request->partner_product_purch_price_id])->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        if($update)
        {
            $index2 = PartnerProductPurchPrice::find($update_id);
            $index2->datetime_end = date('YmdHis', strtotime($request->datetime_start.' -1 second'));
            $index2->save();
        }

        $index->partner_product_id = $request->partner_product_id;
        $index->gross_purch_price  = str_replace('.', '',$request->gross_purch_price);
        $index->flg_tax            = $partnerPulsa->flg_pkp;
        $index->tax_percentage     = $partnerPulsa->flg_pkp == 'Y' ? 10 : 0;
        $index->datetime_start     = date('YmdHis', strtotime($request->datetime_start));
        $index->datetime_end       = date('YmdHis', strtotime($request->datetime_end));

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

        return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Your data has been successfully updated.');
    }

    public function active($id, Request $request)
    {
        $index = PartnerProductPurchPrice::find($id);

        if (!$index) {
            return redirect()->route('partner-product-purch-price.index')->with('gagal', 'Data not exist.');
        }

        /* untuk aktif / non aktif tidak perlu cek datetime start
        if (strtotime('-1 day') >= strtotime($index->datetime_start) && $index->active != 'Y') {
            return redirect()->route('partner-product-purch-price.index')->with('gagal', 'Datetime Start is Expired.')->withInput();
        }
        */

        $checkData = PartnerProductPurchPrice::where('partner_product_id', $index->partner_product_id)
            ->where('active', 'Y')
            ->get();

        $update_id = 0;
        $update    = 0;
        if(!empty($checkData))
        {
            foreach ($checkData as $list) {

                if (strtotime($list->datetime_start) <= strtotime($index->datetime_start) && strtotime($index->datetime_start) <= strtotime($list->datetime_end) && $index->active != 'Y') {
                    $update_id = $list->partner_product_purch_price_id;
                    $update    = 1;
                    break;
                }
            }
        }

        if ($index->version != $request->version) {
            return redirect()->route('partner-product-purch-price.index')->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        if (date('YmdHis', strtotime($index->datetime_end)) < Carbon::now()->format('YmdHis') && $index->active != 'Y') {
            return redirect()->route('partner-product-purch-price.index')->with('gagal', 'Data is outdate, can\'t to active again.');
        }

        if($update)
        {
            $index2 = PartnerProductPurchPrice::find($update_id);
            $index2->datetime_end = date('YmdHis', strtotime($index->datetime_start.' -1 second'));
            $index2->save();
        }

        if ($index->active == 'Y') {

            $index->active              = 'N';
            $index->non_active_datetime = date('YmdHis');

            $index->version += 1;
            $index->update_datetime = date('YmdHis');
            $index->update_user_id  = Auth::id();

            $index->save();

            return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Successfully Nonactive');
        } else {

            $index->active          = 'Y';
            $index->active_datetime = date('YmdHis');

            $index->version += 1;
            $index->update_datetime = date('YmdHis');
            $index->update_user_id  = Auth::id();

            $index->save();

            return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Successfully Activated ');
        }
    }

    public function delete($id, Request $request)
    {
        $index = PartnerProductPurchPrice::find($id);

        if (!$index) {
            return redirect()->route('partner-product-purch-price.index')->with('gagal', 'Data not exist.');
        }

        if ($index->version != $request->version) {
            return redirect()->route('partner-product-purch-price.index')->with('gagal', 'Your data already updated by ' . $index->updatedBy->name . '.');
        }

        $index->delete();

        return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Successfully Deleted ');
    }

    public function ajaxGetProductPartner($partner, $provider)
    {
        $getPartnerProduct = PartnerProduct::select(
            'partner_product_id',
            'partner_product_code',
            'partner_product_name'
        )
            ->where('partner_pulsa_id', $partner)
            ->where('provider_id', $provider)
            ->get();

        return $getPartnerProduct;
    }

    public function yajraGetData(Request $request)
    {

        $f_provider = $request->query('f_provider');
        $f_partner  = $request->query('f_partner');
        $f_active   = $request->query('f_active');

        $getDatas = PartnerProductPurchPrice::leftJoin(
            'sw_partner_product',
            'sw_partner_product.partner_product_id',
            'sw_partner_product_purch_price.partner_product_id'
        )->leftJoin('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', 'sw_partner_product.partner_pulsa_id')
            ->select([
                'sw_partner_pulsa.partner_pulsa_code as partner_pulsa_code',
                'sw_partner_product.partner_product_code as partner_product_code',
                'sw_partner_product.partner_product_name as partner_product_name',
                'partner_product_purch_price_id',
                'gross_purch_price',
                'flg_tax',
                'tax_percentage',
                'datetime_start',
                'datetime_end',
                'sw_partner_product_purch_price.active as active',
                'sw_partner_product_purch_price.version as version',
            ]);

        if ($f_provider != null) {
            $getDatas->where('sw_partner_product.provider_id', $f_provider);
        }
        if ($f_partner != null) {
            $getDatas->where('sw_partner_product.partner_pulsa_id', $f_partner);
        }
        if ($f_active != null) {
            $getDatas->where('sw_partner_product_purch_price.active', $f_active);
        }
        if ($request->f_date != null) {
            $f_date     = date('YmdHis', strtotime($request->f_date.' 23:59:59'));
            $getDatas->where('datetime_start', '<=', $f_date)
                ->where('datetime_end', '>=', $f_date);
        }
        $getDatas = $getDatas->get();

        $start      = 1;
        $Datatables = Datatables::of($getDatas)
            ->addColumn('slno', function ($getData) use (&$start) {
                return $start++;
            })
            ->editColumn('gross_purch_price', function ($getData) {
                return 'Rp. ' . number_format($getData->gross_purch_price, 2);
            })
            ->editColumn('flg_tax', function ($getData) {
                if ($getData->flg_tax == 'Y') {
                    return "Y";
                } else {
                    return "N";
                }
            })
            ->editColumn('tax_percentage', function ($getData) {
                if ($getData->flg_tax == "Y") {
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
                if (Auth::user()->can('update-partner-product-purch-price')) {
                    $actionHtml = $actionHtml."
                        <a href='" . route('partner-product-purch-price.edit', ['id' => $getData->partner_product_purch_price_id]) . "'' class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></a>";
                }

                if (Auth::user()->can('activate-partner-product-purch-price')) {
                  if ($getData->active == 'Y') {
                    $actionHtml = $actionHtml."<a href='' class='unpublish' data-value='" . $getData->partner_product_purch_price_id . "' data-version='" . $getData->version . "' data-toggle='modal' data-target='.modal-nonactive'><span class='btn btn-dark btn-xs' data-toggle='tooltip' data-placement='top' title='Non Active'><i class='fa fa-times'></i></span></a>";
                  }
                  else
                  {
                    $actionHtml = $actionHtml."<a href='' class='publish' data-value='" . $getData->partner_product_purch_price_id . "' data-version='" . $getData->version . "' data-toggle='modal' data-target='.modal-active'> <span class='btn btn-success btn-xs' data-toggle='tooltip' data-placement='top' title='Active'><i class='fa fa-check'></i></span></a>";
                  }

                }

                if (Auth::user()->can('delete-partner-product-purch-price')) {
                    $actionHtml = $actionHtml."<a href='' class='delete' data-value='" . $getData->partner_product_purch_price_id . "' data-version='" . $getData->version . "' data-toggle='modal' data-target='.modal-delete'> <span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></span></a>";
                }
                return $actionHtml;
            });

        if (Auth::user()->can('activate-partner-product-purch-price')) {
            $Datatables = $Datatables->editColumn('active', function ($getData) {
                if ($getData->active == 'Y') {
                    return "
                        <a
                            href=''
                            class='unpublish'
                            data-value='" . $getData->partner_product_purch_price_id . "'
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
                            data-value='" . $getData->partner_product_purch_price_id . "'
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
        $getPartner = PartnerPulsa::where('active', 'Y')->get();

        return view('partner-product-purchase-price.masal', compact('getPartner'));
    }

    public function template(Request $request)
    {
        $partnerProduct = PartnerProduct::select('partner_product_code', 'partner_product_name', 'active')
            ->orderBy('partner_product_code', 'desc')
            ->where('partner_pulsa_id', $request->partner_pulsa_id)
            ->get()
            ->toArray();

        if(!$partnerProduct){
          return redirect()->route('partner-product-purch-price.upload')->with('gagal', 'Supplier does not have a product.')->withInput();
        }

        return Excel::create('Template Partner Product Purch Price Import', function ($excel) use ($partnerProduct) {
          $excel->sheet('Data-Import', function ($sheet) {
              $sheet->row(1, array('partner_product_code', 'gross_purch_price', 'datetime_start', 'active'));
              $sheet->setColumnFormat(array(
                  'A' => '',
                  'B' => '0',
                  'C' => 'YYYY-MM-DD HH:mm:ss',
                  'D' => '',
              ));
          });

          $excel->sheet('partner_product_id', function ($sheet) use ($partnerProduct) {
              $sheet->fromArray($partnerProduct, null, 'A6', true);
              $sheet->row(1, array('Example'));
              $sheet->mergeCells('A1:E1');
              $sheet->row(2, array('partner_product_code', 'gross_purch_price', 'datetime_start', 'active'));
              $sheet->row(3, array('PP1', '45000', '2017-07-01 12:00:00', 'Y'));
              $sheet->row(5, array('Data Partner Product'));
              $sheet->mergeCells('A5:C5');
              $sheet->row(6, array('partner_product_code', 'partner_product_name', 'active'));
              $sheet->setAllBorders('thin');
              $sheet->setFreeze('A7');

              $sheet->cells('A2:F3', function ($cells) {
                  $cells->setBackground('#5c92e8');
                  $cells->setFontColor('#000000');
                  $cells->setFontWeight('bold');
              });

              $sheet->cells('A6:C6', function ($cells) {
                  $cells->setBackground('#000000');
                  $cells->setFontColor('#ffffff');
                  $cells->setFontWeight('bold');
              });
          });

        })->download('xls');
    }

    public function prosesTemplate(Request $request)
    {
        $getPartner = PartnerPulsa::where('active', 'Y')->get();

        if ($request->hasFile('file')) {
          $path = Input::file('file')->getRealPath();
          $data = Excel::selectSheets('Data-Import')->load($path, function ($reader) {
          })->get();

          $getName = PartnerPulsa::where('partner_pulsa_id', $request->partner_pulsa_id)->first();

          if (!empty($data) && $data->count()) {
              foreach ($data as $key) {
                  $collect[] = [
                      'partner_product_code' => $key->partner_product_code,
                      'gross_purch_price'    => $key->gross_purch_price,
                      'datetime_start'       => $key->datetime_start,
                      'datetime_end'         => $key->datetime_end,
                      'active'               => $key->active,
                  ];
              }

              if (!empty($collect)) {
                  $collect = collect($collect);

                  return view('partner-product-purchase-price.masal', compact('collect','getPartner','getName'));
              }
          } else {
              return view('partner-product-purchase-price.masal', compact('getPartner'))->with('gagal', 'Please Download Template');
          }
        } else {
            return view('partner-product-purchase-price.masal', compact('getPartner'))->with('gagal', 'Please Select Template');
        }
    }

    public function storeTemplate(Request $request)
    {
        // return $request->all();

        $partner_product_code = $request->partner_product_code;
        $gross_purch_price    = $request->gross_purch_price;
        //$tax_percentage       = $request->tax_percentage;
        $datetime_start       = $request->datetime_start;
        $datetime_end         = '2037-12-31 23:59:59';
        $active               = $request->active;

        $time_commit = strtotime('-1 day');

        // DB::transaction(function () use ($partner_product_id, $gross_purch_price, $tax_percentage, $datetime_start, $datetime_end, $active) {

        foreach ($partner_product_code as $key => $n) {
            /*Load array */

            $skip            = 0;
            $update          = 0;
            $update_id       = 0;
            $partner_product = PartnerProduct::where('partner_product_code', strtoupper($partner_product_code[$key]))->where('partner_pulsa_id', $request->partner_pulsa_id)->first();

            // return $partner_product;
            if ($partner_product) {
                $checkData = PartnerProductPurchPrice::where('partner_product_id', $partner_product->partner_product_id)
                    ->where('active', 'Y')
                    ->get();
                $partnerPulsa = PartnerPulsa::find($request->partner_pulsa_id);
            } else {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Data Partner Product not found</span></h4>';
                }
                $skip = 1;
            }

            if ($gross_purch_price[$key] == '') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Gross Purchase Price is empty</span></h4>';
                }
                $skip = 1;
            }

            /*if ($tax_percentage[$key] == '') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Tax Percentage is empty</span></h4>';
                }
                $skip = 1;
            }*/

            if ($datetime_start[$key] == '') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Datetime Start is empty</span></h4>';
                }
                $skip = 1;
            }

            // if ($datetime_end[$key] == '') {
            //     if (!$skip) {
            //         $message = '<h4><span class="label label-danger">Datetime end is empty</span></h4>';
            //     }
            //     $skip = 1;
            // }

            if (strtotime($datetime_start[$key]) > strtotime($datetime_end)) {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Datetime start is bigger than Datetime end</span></h4>';
                }
                $skip = 1;
            }

            // foreach ($checkData as $list) {
            //     if (strtotime($list->datetime_start) <= strtotime($datetime_start[$key]) && strtotime($datetime_start[$key]) <= strtotime($list->datetime_end) && strtoupper($active[$key]) == 'Y') {
            //         if (!$skip) {
            //             $message = '<h4><span class="label label-danger">Data still active</span></h4>';
            //         }
            //         $skip = 1;
            //     }
            //     if (strtotime($list->datetime_start) <= strtotime($datetime_end[$key]) && strtotime($datetime_end[$key]) <= strtotime($list->datetime_end) && strtoupper($active[$key]) == 'Y') {
            //         if (!$skip) {
            //             $message = '<h4><span class="label label-danger">Data still active</span></h4>';
            //         }
            //         $skip = 1;
            //     }
            //     if (strtotime($datetime_start[$key]) <= strtotime($list->datetime_start) && strtotime($list->datetime_end) <= strtotime($datetime_end[$key]) && strtoupper($active[$key]) == 'Y') {
            //         if (!$skip) {
            //             $message = '<h4><span class="label label-danger">Data still active</span></h4>';
            //         }
            //         $skip = 1;
            //     }
            // }

            if ($time_commit >= strtotime($datetime_start[$key]) && strtoupper($active[$key]) == 'Y') {
                if (!$skip) {
                    $message = '<h4><span class="label label-danger">Datetime Start is Expired</span></h4>';
                }
                $skip = 1;
            }

            if(!empty($checkData))
            {
                foreach ($checkData as $list) {

                    if (strtotime($list->datetime_start) <= strtotime($datetime_start[$key]) && strtotime($datetime_start[$key]) <= strtotime($list->datetime_end) && strtoupper($active[$key]) == 'Y') {
                        $update_id = $list->partner_product_purch_price_id;
                        $update    = 1;
                        break;
                    }
                }
            }


            if($update && !$skip)
            {
                $index = PartnerProductPurchPrice::find($update_id);
                $index->datetime_end = date('YmdHis', strtotime($datetime_start[$key].' -1 second'));
                $index->save();
            }

            if (!$skip) {
                PartnerProductPurchPrice::insert(
                [
                    "partner_product_id"  => $partner_product->partner_product_id,
                    "gross_purch_price"   => $gross_purch_price[$key],
                    "flg_tax"             => $partnerPulsa->flg_pkp,
                    "tax_percentage"      => $partnerPulsa->flg_pkp == 'Y' ? 10 : 0,
                    "datetime_start"      => date('YmdHis', strtotime($datetime_start[$key])),
                    "datetime_end"        => date('YmdHis', strtotime($datetime_end)),
                    "create_user_id"      => Auth::id(),
                    "active"              => strtoupper($active[$key]),
                    "active_datetime"     => strtoupper($active[$key]) == 'Y' ? date('YmdHis') : '00000000000000',
                    "non_active_datetime" => strtoupper($active[$key]) != 'Y' ? date('YmdHis') : '00000000000000',
                    "version"             => 0,
                    "create_datetime"     => date('YmdHis'),
                    "create_user_id"      => Auth::id(),
                    "update_datetime"     => '00000000000000',
                    "update_user_id"      => 0,
                ]);

                $pass[] = [
                    'row'                  => $key,
                    'partner_product_code' => $partner_product_code[$key],
                    'gross_purch_price'    => $gross_purch_price[$key],
                    "tax_percentage"       => $partnerPulsa->flg_pkp == 'Y' ? 10 : 0,
                    'datetime_start'       => $datetime_start[$key],
                    'datetime_end'         => $datetime_end,
                    'active'               => $active[$key],
                ];
            } else {
                $error[] = [
                    'row'                  => $key,
                    'partner_product_code' => $partner_product_code[$key],
                    'gross_purch_price'    => $gross_purch_price[$key],
                    "tax_percentage"       => $partnerPulsa->flg_pkp == 'Y' ? 10 : 0,
                    'datetime_start'       => $datetime_start[$key],
                    'datetime_end'         => $datetime_end,
                    'active'               => $active[$key],
                    'message'              => $message,
                ];
            }
        }
        // });

        $getPartner = PartnerPulsa::where('active', 'Y')->get();

        if (!empty($error)) {

            $error = collect($error);

            if (!empty($pass)) {
                $pass = collect($pass);
            } else {
                $pass = '';
            }

            return view('partner-product-purchase-price.masal', compact('error', 'pass','getPartner'));
        }

        return redirect()->route('partner-product-purch-price.index')->with('berhasil', 'Your data has been successfully uploaded.');
    }

}
