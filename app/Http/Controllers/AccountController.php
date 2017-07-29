<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;


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
        $getRole = Role::get();

        return view('account.role', compact('getRole'));
    }

    public function roleUbah($id)
    {
        $getRole = Role::find($id);

        if(!$getRole){
          return view('errors.404');
        }

        $can = array();
        foreach ($getRole->permissions as $key => $value) {
          $can[] = $key;
        }

        return view('account.role-edit', compact('getRole', 'can'));
    }

    public function roleEdit(Request $request)
    {

      $task = array();
      foreach ($request->permissions as $key => $value) {
        $task = array($key=>$value);
      }

      $update = Role::find($request->id);
      $update->name = $request->name;
      $update->permissions = $task;
      $update->update();

      return redirect()->route('account.role')->with('berhasil', 'value');
    }
}
