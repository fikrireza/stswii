<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;



class AccountController extends Controller
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
        return view('account.index');
    }

    public function tambah()
    {
        return view('account.tambah');
    }

    public function role()
    {
        return view('account.role');
    }
}
