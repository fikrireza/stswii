<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Provider;

class ReportController extends Controller
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

    public function bySupplier()
    {

        return view('report.supplier');
    }

    public function byAgent()
    {

        return view('report.agent');
    }

    public function byProvider()
    {

        return view('report.provider');
    }

    public function byTopUpDepositPartner()
    {

        return view('report.depositPartner');
    }
}
