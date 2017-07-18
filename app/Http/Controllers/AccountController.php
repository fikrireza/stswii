<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;



class AccountController extends Controller
{

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
