<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;

use App\Models\User;
use App\Models\PartnerPulsa;

use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class PartnerPulsaController extends Controller
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


    public function index(){
      $getPartner = PartnerPulsa::select(
          'partner_pulsa_id',
          'partner_pulsa_code',
          'partner_pulsa_name',
          'description',
          'type_top',
          'payment_termin',
          'active',
          'version'
        )
        ->get();

    	return view('partner-pulsa.index', compact('getPartner'));
    }

    public function create(){
        $tahun = date('y');
        $bulan = date('m');
        $rand = rand(1000,9999);

        $partner_pulsa_code = 'SPT-'.$tahun.'-'.$bulan.'-'.$rand;

        $cek_kode = PartnerPulsa::where('partner_pulsa_code', $partner_pulsa_code)->first();

        if(!$cek_kode){
          $partner_pulsa_code;
        }else{
          $partner_pulsa_code = 'Partner Pulsa Code is Empty - Please Contact Amadeo';
        }

      	return view('partner-pulsa.tambah', compact('partner_pulsa_code'));
    }

    public function store(Request $request){
      $message = [
  			'partner_pulsa_code.required' => 'mohon isi',
  			'partner_pulsa_code.max' => 'Terlalu Panjang, Maks 25 Karakter',
  			'partner_pulsa_code.unique' => 'Produk ini sudah ada',
  			'partner_pulsa_name.required' => 'mohon isi',
        'description.required' => 'mohon isi',
        'type_top.required' => 'mohon isi',
  			// 'payment_termin.required' => 'mohon isi',
  		];

  		$validator = Validator::make($request->all(), [
  			'partner_pulsa_code' => 'required|unique:sw_partner_pulsa',
  			'partner_pulsa_name' => 'required',
        'description' => 'required',
        'type_top' => 'required',
  			// 'payment_termin' => 'required',
  		], $message);

  		if($validator->fails()){
  			return redirect()->route('partner-pulsa.create')
  				->withErrors($validator)->withInput();
  		}

  		DB::transaction(function () use($request) {
  			$save = new PartnerPulsa;

  			$save->partner_pulsa_code  = $request->partner_pulsa_code;
  			$save->partner_pulsa_name  = $request->partner_pulsa_name;
        $save->description         = $request->description;
        $save->type_top            = $request->type_top;
  			$save->payment_termin      = $request->type_top == 'TERMIN' ? 1 : 0;
  			$save->active              = /*isset($request->active) ? */1/* : 0*/;
  			$save->active_datetime     = Carbon::now()->format('YmdHis');
  			$save->non_active_datetime = '';
  			$save->version 		         = 0;
  			$save->create_user_id	     = Auth::user()->id;
        $save->create_datetime     = Carbon::now()->format('YmdHis');
        $save->update_user_id      = 0;/*Auth::user()->id*/
        $save->update_datetime     = '';
  			$save->save();
  		});

  		return redirect()->route('partner-pulsa.index')
        ->with('alret', 'alert-success')
        ->with('berhasil', 'Berhasil Menambahkan Partner '.$request->partner_pulsa_name);
    }

    public function active($id, $version, $status){
      $getPartnerPulsa = PartnerPulsa::find($id);

      if($getPartnerPulsa == null){
        $info = 'Status Partner gagal diubah! Tidak dapat menemukan Partner!';
        $alret = 'alert-danger';
      }
      else if($getPartnerPulsa->version != $version){
        $User = User::find($getPartnerPulsa->update_user_id);
        $info = 'Status Partner gagal diubah! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil Memperbarui Status Partner '.$getPartnerPulsa->partner_pulsa_name;
        $alret = 'alert-success';
        DB::transaction(function () use($getPartnerPulsa, $status) {
          $getPartnerPulsa->increment('version');
          $getPartnerPulsa->active         = $status;
          $getPartnerPulsa->update_user_id = Auth::user()->id;
          $getPartnerPulsa->update_datetime= Carbon::now()->format('YmdHis');
          if($status == 1){
            $getPartnerPulsa->active_datetime  = Carbon::now()->format('YmdHis');
          }
          else if($status == 0){
            $getPartnerPulsa->non_active_datetime  = Carbon::now()->format('YmdHis');
          }
          $getPartnerPulsa->update();
        });
      }
      return redirect()->route('partner-pulsa.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function edit($id, $version){
      $getPartnerPulsa = PartnerPulsa::find($id);

      if($getPartnerPulsa == null){
        $retrun = 'index';
        $info = 'Tidak dapat menemukan Partner!';
        $alret = 'alert-danger';
      }
      else if($getPartnerPulsa->version != $version){
        $User = User::find($getPartnerPulsa->update_user_id);
        $retrun = 'index';
        $info = 'Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $retrun = 'update';
      }

      if($retrun == 'index'){
        return redirect()->route('partner-pulsa.index')
          ->with('alret', $alret)
          ->with('berhasil', $info);
      }
      else if($retrun == 'update'){
      	return view('partner-pulsa.ubah', compact('getPartnerPulsa'));
      }
    }

    public function update($id, $version, Request $request){
      $message = [
        'partner_pulsa_name.required' => 'mohon isi',
        'description.required' => 'mohon isi',
        'type_top.required' => 'mohon isi',
        // 'payment_termin.required' => 'mohon isi',
      ];

      $validator = Validator::make($request->all(), [
        'partner_pulsa_name' => 'required',
        'description' => 'required',
        'type_top' => 'required',
        // 'payment_termin' => 'required',
      ], $message);

  		if($validator->fails()){
  			return redirect()
          ->route('partner-pulsa.edit', ['id' => $id, 'version' => $version])
  				->withErrors($validator)
          ->withInput();
  		}

      $update = PartnerPulsa::find($request->partner_pulsa_id);

      if($update == null){
        $info = 'Data Partner gagal diubah! Tidak dapat menemukan Partner!';
        $alret = 'alert-danger';
      }
      else if($update->version != $request->version){
        $User = User::find($update->update_user_id);
        $info = 'Data Partner gagal diubah! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil Memperbarui Data Partner '.$update->partner_pulsa_name;
        $alret = 'alert-success';
        DB::transaction(function () use($update, $request) {
          $update->increment('version');
          $update->partner_pulsa_name = $request->partner_pulsa_name;
          $update->description        = $request->description;
          $update->type_top           = $request->type_top;
          $update->payment_termin     = $request->type_top == 'TERMIN' ? 1 : 0;
          $update->update_user_id     = Auth::user()->id;
          $update->update_datetime    = Carbon::now()->format('YmdHis');
          $update->update();
        });
      }
  		return redirect()->route('partner-pulsa.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function delete($id, $version){
      $getPartnerPulsa = PartnerPulsa::find($id);

      if($getPartnerPulsa == null){
        $info = 'Data Partner gagal dihapus! Tidak dapat menemukan Partner!';
        $alret = 'alert-danger';
      }
      else if($getPartnerPulsa->version != $version){
        $User = User::find($getPartnerPulsa->update_user_id);
        $info = 'Data Partner gagal dihapus! Data Partner telah diupdate Oleh '.$User->name.'. Harap periksa kembali!';
        $alret = 'alert-danger';
      }
      else{
        $info = 'Berhasil menghapus Partner Pulsa ';
        $alret = 'alert-success';
        $getPartnerPulsa->delete();
      }
      
      return redirect()->route('partner-pulsa.index')
        ->with('alret', $alret)
        ->with('berhasil', $info);
    }

    public function yajraGetData(){

      $getDatas = PartnerPulsa::select([
        'partner_pulsa_id',
        'partner_pulsa_code',
        'partner_pulsa_name',
        'description',
        'type_top',
        'payment_termin',
        'active',
        'version'
        ])
        ->get();

      $start=1;
      $Datatables = Datatables::of($getDatas)
          ->addColumn('slno', function ($getData) use (&$start) {
              return $start++;
          })
          ->addColumn('action', function ($getData) {
            $actionHtml = '';
            if (Auth::user()->can('update-partner-pulsa')) {
              $actionHtml = $actionHtml." 
                <a
                class='update'
                href='".route('partner-pulsa.edit', ['id' => $getData->partner_pulsa_id, 'version' => $getData->version])."'
              >
                <span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></span>
              </a>";
            }
            if (Auth::user()->can('delete-partner-pulsa')) {
              $actionHtml = $actionHtml."
                <a 
                  href='' 
                  class='delete' 
                  data-value='".$getData->partner_pulsa_id."'
                  data-version='".$getData->version."' 
                  data-toggle='modal' 
                  data-target='.modal-delete'
                >
                  <span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus'><i class='fa fa-remove'></i></span>
                </a>";
            }
              return $actionHtml;
          });

      if (Auth::user()->can('activate-partner-pulsa')) {
        $Datatables = $Datatables->editColumn('active', function ($getData){
        if($getData->active == 1){
          return "
            <a 
              href='' 
              class='unpublish' 
              data-value='".$getData->partner_pulsa_id."' 
              data-version='".$getData->version."' 
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
        }
        else if($getData->active == 0){
          return "
            <a 
              href='' 
              class='publish' 
              data-value='".$getData->partner_pulsa_id."' 
              data-version='".$getData->version."' 
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
}
