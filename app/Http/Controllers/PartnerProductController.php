<?php

namespace App\Http\Controllers;

use App\Models\PartnerProduct;
use App\Models\PartnerPulsa;
use App\Models\Product;
use App\Models\Provider;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Facades\Datatables;

class PartnerProductController extends Controller
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
		];

		$validator = Validator::make($request->all(), [
			'f_provider' => 'integer|nullable',
			'f_partner'  => 'integer|nullable',
		], $message);

		if ($validator->fails()) {
			return redirect()->route('product-sell-price.index');
		}

		return view('partner-product.index', compact(
			'request',
			'provider',
			'partner'
		));
	}

	public function create()
	{

		$tahun = date('y');
		$bulan = date('m');
		$rand  = rand(1000, 9999);

		$partner_product_code = 'SPT-' . $tahun . '-' . $bulan . '-' . $rand;

		$cek_kode = PartnerProduct::where('partner_product_code', $partner_product_code)->first();

		if (!$cek_kode) {
			$partner_product_code;
		} else {
			$partner_product_code = 'Partner Pulsa Code is Empty - Please Contact Amadeo';
		}

		$getPartner = PartnerPulsa::select(
			'partner_pulsa_id',
			'partner_pulsa_code',
			'partner_pulsa_name'
		)
			->get();

		// Ambil semua provider

		// $getProvider = Provider::select(
		// 'provider_id',
		// 'provider_code',
		// 'provider_name'
		// )
		// ->get();

		// Ambil semua provider bila ada product

		$getProvider = Provider::join('sw_product', 'sw_product.provider_id', '=', 'sw_provider.provider_id')->select(
			'sw_provider.provider_id',
			'provider_code',
			'provider_name'
		)
			->distinct()
			->get();

		return view('partner-product.tambah', compact(
			'partner_product_code',
			'getPartner',
			'getProvider'
		));
	}

	public function store(Request $request)
	{
		$message = [
			'partner_product_code.required' => 'This field required',
			'partner_product_code.max'      => 'Maximum character 25',
			// 'partner_product_code.unique'   => 'Produk ini sudah ada',
			'partner_product_name.required' => 'This field required',
			'partner_pulsa_id.required'     => 'This field required',
			'provider_id.required'          => 'This field required',
			'product_id.required'           => 'This field required',
			'product_id.unique'							=> 'This product already exist',
		];

		$validator = Validator::make($request->all(), [
			// 'partner_product_code' => 'required|unique:sw_partner_product|max:25',
			'partner_product_code' => 'required|max:25',
			'partner_product_name' => 'required',
			'provider_id'          => 'required',
			'partner_pulsa_id'     => 'required',
			'product_id'           => 'required|unique:sw_partner_product,product_id,NULL,partner_product_id,partner_pulsa_id,'.$request->partner_pulsa_id,
		], $message);

		if ($validator->fails()) {
			return redirect()->route('partner-product.create')
				->withErrors($validator)->withInput();
		}

		DB::transaction(function () use ($request) {
			$save = new PartnerProduct;

			$save->partner_product_code = strtoupper($request->partner_product_code);
			$save->partner_product_name = strtoupper($request->partner_product_name);
			$save->partner_pulsa_id     = $request->partner_pulsa_id;
			$save->provider_id          = $request->provider_id;
			$save->product_id           = $request->product_id;

			$save->active              = isset($request->active) ? 'Y' : 'N';
			$save->active_datetime     = isset($request->active) ? Carbon::now()->format('YmdHis') : '00000000000000';
			$save->non_active_datetime = !isset($request->active) ? Carbon::now()->format('YmdHis') : '00000000000000';
			$save->version             = 0;
			$save->create_user_id      = Auth::user()->id;
			$save->create_datetime     = Carbon::now()->format('YmdHis');
			$save->update_user_id      = 0; /*Auth::user()->id*/
			$save->update_datetime     = '00000000000000';
			$save->save();
		});

		return redirect()->route('partner-product.index')
											->with('alret', 'alert-success')
											->with('berhasil', 'Your data has been successfully saved. ' . $request->partner_product_name);
	}

	public function edit($id, $version)
	{
		$getPartnerProduct = PartnerProduct::find($id);

		if ($getPartnerProduct == null) {
			$retrun = 'index';
			$info   = 'Partner Product falied to update! Partner Product not found!';
			$alret  = 'alert-danger';
		} else if ($getPartnerProduct->version != $version) {
			$retrun = 'index';
			$User   = User::find($getPartnerProduct->update_user_id);
			$info   = 'Partner Product falied to update! Partner Product already updated by ' . $User->name . '. Please Check Again!';
			$alret  = 'alert-danger';
		} else {
			$retrun     = 'update';
			$getPartner = PartnerPulsa::select('partner_pulsa_id','partner_pulsa_code','partner_pulsa_name')->get();

			// Ambil semua provider

			// $getProvider = Provider::select(
			// 'provider_id',
			// 'provider_code',
			// 'provider_name'
			// )
			// ->get();

			// Ambil semua provider bila ada product

			$getProvider = Provider::join('sw_product', 'sw_product.provider_id', '=', 'sw_provider.provider_id')
													->select('sw_provider.provider_id','provider_code','provider_name')
													->distinct()
													->get();

			$getProduct = Product::select('product_id','product_code','product_name')
																		->where('provider_id', $getPartnerProduct->provider_id)
																		->get();
		}

		if ($retrun == 'index') {
			return redirect()->route('partner-product.index')
												->with('alret', $alret)
												->with('berhasil', $info);
		} else if ($retrun == 'update') {
			return view('partner-product.ubah', compact('getPartnerProduct',
																									'getPartner',
																									'getProvider',
																									'getProduct'
																								));
		}
	}

	public function update($id, $version, Request $request)
	{
		$message = [
			'partner_product_code.required' => 'This field required',
			'partner_product_code.max'      => 'Maximum character 25',
			'partner_product_name.required' => 'This field required',
			'partner_pulsa_id.required'     => 'This field required',
			'provider_id.required'          => 'This field required',
			'product_id.required'           => 'This field required',
			'product_id.unique'							=> 'This product already exist',
		];

		$validator = Validator::make($request->all(), [
			// 'partner_product_code' => 'required|max:25|unique:sw_partner_product,partner_product_code,' . $request->partner_product_id . ',partner_product_id',
			'partner_product_code' => 'required|max:25',
			'partner_product_name' => 'required',
			'partner_pulsa_id'     => 'required',
			'provider_id'          => 'required',
			'product_id'           => 'required|unique:sw_partner_product,partner_product_id,'.$request->partner_product_id.',product_id,partner_pulsa_id,'.$request->partner_pulsa_id,

		], $message);

		if ($validator->fails()) {
			return redirect()->route('partner-product.edit', ['id' => $id, 'version' => $version])
				->withErrors($validator)->withInput();
		}

		$update = PartnerProduct::find($request->partner_product_id);

		if ($update == null) {
			$info  = 'Partner Product falied to update! Partner Product not found!';
			$alret = 'alert-danger';
		} else if ($update->version != $request->version) {
			$User  = User::find($update->update_user_id);
			$info  = 'Partner Product falied to update! Partner Product already updated by ' . $User->name . '. Please Check Again!';
			$alret = 'alert-danger';
		} else {
			$info  = 'Your data has been successfully updated. ' . $update->partner_pulsa_name;
			$alret = 'alert-success';
			DB::transaction(function () use ($update, $request) {
				$update->increment('version');
				$update->partner_product_code = strtoupper($request->partner_product_code);
				$update->partner_product_name = strtoupper($request->partner_product_name);
				$update->partner_pulsa_id     = $request->partner_pulsa_id;
				$update->provider_id          = $request->provider_id;
				$update->product_id           = $request->product_id;
				$update->active               = isset($request->active) ? 'Y' : 'N';
				$update->active_datetime      = isset($request->active) ? Carbon::now()->format('YmdHis') : '00000000000000';
				$update->non_active_datetime  = !isset($request->active) ? Carbon::now()->format('YmdHis') : '00000000000000';
				$update->update_user_id       = Auth::user()->id;
				$update->update_datetime      = Carbon::now()->format('YmdHis');
				$update->update();
			});
		}

		return redirect()->route('partner-product.index')
			->with('alret', $alret)
			->with('berhasil', $info);
	}

	public function active($id, $version, $status)
	{
		$getPartnerProduct = PartnerProduct::find($id);

		if ($getPartnerProduct == null) {
			$info  = 'Partner Product falied to update! Partner Product not found!';
			$alret = 'alert-danger';
		} else if ($getPartnerProduct->version != $version) {
			$User  = User::find($getPartnerProduct->update_user_id);
			$info  = 'Partner Product falied to update! Partner Product already updated by ' . $User->name . '. Please Check Again!';
			$alret = 'alert-danger';
		} else {
			$info  = 'Your data has been successfully updated ' . $getPartnerProduct->partner_product_name;
			$alret = 'alert-success';
			DB::transaction(function () use ($getPartnerProduct, $status) {
				$getPartnerProduct->increment('version');
				$getPartnerProduct->active          = $status;
				$getPartnerProduct->update_user_id  = Auth::user()->id;
				$getPartnerProduct->update_datetime = Carbon::now()->format('YmdHis');
				if ($status == 1) {
					$getPartnerProduct->active_datetime = Carbon::now()->format('YmdHis');
				} else if ($status == 0) {
					$getPartnerProduct->non_active_datetime = Carbon::now()->format('YmdHis');
				}
				$getPartnerProduct->update();
			});
		}
		return redirect()->route('partner-product.index')
			->with('alret', $alret)
			->with('berhasil', $info);
	}

	public function delete($id, $version)
	{
		$getPartnerProduct = PartnerProduct::find($id);

		if ($getPartnerProduct == null) {
			$info  = 'Partner Product falied to delete! Partner Product not found!';
			$alret = 'alert-danger';
		} else if ($getPartnerProduct->version != $version) {
			$User  = User::find($getPartnerProduct->update_user_id);
			$info  = 'Partner Product falied to delete! Partner Product already updated by ' . $User->name . '. Please Check Again!';
			$alret = 'alert-danger';
		} else {
			$info  = 'Data Successfully Deleted ';
			$alret = 'alert-success';
			$getPartnerProduct->delete();
		}
		return redirect()->route('partner-product.index')
			->with('alret', $alret)
			->with('berhasil', $info);
	}

	public function ajaxGetProductList($id = 0)
	{
		$getProduct = Product::where('provider_id', $id)->get();

		return $getProduct;
	}

	public function yajraGetData(Request $request)
	{

		$f_provider = $request->query('f_provider');
		$f_partner  = $request->query('f_partner');

		$getDatas = PartnerProduct::leftJoin('sw_partner_pulsa', 'sw_partner_pulsa.partner_pulsa_id', 'sw_partner_product.partner_pulsa_id')
			->leftJoin('sw_provider', 'sw_provider.provider_id', 'sw_partner_product.provider_id')
			->leftJoin('sw_product', 'sw_product.product_id', 'sw_partner_product.product_id')
			->select([
				'sw_partner_pulsa.partner_pulsa_code as partner_pulsa_code',
				'sw_partner_pulsa.partner_pulsa_name as partner_pulsa_name',
				'sw_provider.provider_code as provider_code',
				'sw_provider.provider_name as provider_name',
				'sw_product.product_code as product_code',
				'sw_product.product_name as product_name',
				'partner_product_id',
				'partner_product_code',
				'partner_product_name',
				'sw_partner_product.active as active',
				'sw_partner_product.version as version',
			]);

		if ($f_provider != null) {
			$getDatas->where('sw_partner_product.provider_id', $f_provider);
		}
		if ($f_partner != null) {
			$getDatas->where('sw_partner_product.partner_pulsa_id', $f_partner);
		}
		$getDatas = $getDatas->get();

		$start      = 1;
		$Datatables = Datatables::of($getDatas)
			->addColumn('slno', function ($getData) use (&$start) {
				return $start++;
			})
			->editColumn('partner_pulsa_code', function ($getData) {
				return $getData->partner_pulsa_code . ' - ' . $getData->partner_pulsa_name;
			})
			->editColumn('provider_code', function ($getData) {
				return $getData->provider_code . ' - ' . $getData->provider_name;
			})
			->editColumn('product_code', function ($getData) {
				return $getData->product_code . ' - ' . $getData->product_name;
			})
			->addColumn('action', function ($getData) {
				$actionHtml = '';
				if (Auth::user()->can('update-partner-product')) {
					$actionHtml = $actionHtml . "
				<a
					href='" . route('partner-product.edit', ['id' => $getData->partner_product_id, 'version' => $getData->version]) . "''
					class='btn btn-xs btn-warning btn-sm'
					data-toggle='tooltip'
					data-placement='top'
					title='Ubah'
				><i class='fa fa-pencil'></i></a>";
				}
				if (Auth::user()->can('delete-partner-product')) {
					$actionHtml = $actionHtml . "
						<a
							href=''
							class='delete'
							data-value='" . $getData->partner_product_id . "'
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
						</a>
					";
				}
				return $actionHtml;
			});

		if (Auth::user()->can('activate-product-sell-price')) {
			$Datatables = $Datatables->editColumn('active', function ($getData) {
				if ($getData->active == "Y") {
					return
					"
						<a
							href=''
							class='unpublish'
							data-value='" . $getData->partner_product_id . "'
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
						</a><br>
					";
				} else if ($getData->active == "N") {
					return
					"
						<a
							href=''
							class='publish'
							data-value='" . $getData->partner_product_id . "'
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
						</a><br>
					";
				}
			});
		}

		$Datatables = $Datatables
			->escapeColumns(['*'])
			->make(true);

		return $Datatables;
	}
}
