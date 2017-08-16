<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PalomaDepositTrx;

use Auth;

class PalomaDepositTrxController extends Controller
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
        $getData = PalomaDepositTrx::get();

        return view('paloma-transaction.index', compact('getData'));
    }

    public function update($id, Request $request)
    {
        $getData = PalomaDepositTrx::find($id);

        if (!$getData) {
            return redirect()->route('palomaDeposit.index')->with('gagal', 'Data not exist.');
        }

        if ($getData->version != $request->version) {
            return redirect()->route('palomaDeposit.index')->with('gagal', 'Your data already updated by ' . $getData->updatedBy->name . '.');
        }

        $getData->status = 'R';
        $getData->version += 1;
        $getData->update_datetime = date('YmdHis');
        $getData->update_user_id  = Auth::id();
        $getData->update();

        return redirect()->route('palomaDeposit.index')->with('berhasil', 'Successfully Confirm');
    }
}
