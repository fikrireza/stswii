<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;

use DB;
use Auth;
use Validator;
use Hash;

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
        $getUser = User::with('roles')->get();

        return view('account.index', compact('getUser'));
    }

    public function tambah()
    {
        $getRole = Role::get();

        return view('account.tambah', compact('getRole'));
    }

    public function store(Request $request)
    {
        $message = [
          'name.required' => 'Wajib di isi',
          'email.required' => 'Wajib di isi',
          'email.email' => 'Format email',
          'email.unique' => 'Email sudah dipakai',
        ];

        $validator = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|email|unique:sw_users'
        ], $message);

        if($validator->fails())
        {
          return redirect()->route('account.tambah')->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
          $confirmation_code = str_random(30).time();
          $userSave = User::create([
            'name' => $request->name,
            'avatar' => 'userdefault.png',
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'confirmed' => 0,
            'login_count' => 0,
            'confirmation_code' => $confirmation_code,
          ]);

          foreach ($request->role as $key) {
            $role = RoleUser::firstOrCreate(['user_id' => $userSave->id, 'role_id' => $key]);
          }

          // $data = array([
          //     'name' => $request->name,
          //     'email' => $request->email,
          //     'confirmation_code' => $confirmation_code
          //   ]);
          //
          // Mail::send('email.confirm', ['data' => $data], function($message) use ($data) {
          //   $message->to($data[0]['email'], $data[0]['name'])->subject('Aktifasi Akun Web Admin');
          // });

        });

        return redirect()->route('account.index')->with('berhasil', 'Akun baru sudah dibuat, cek '.$request->email.' untuk verifiakasi');
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
      // return str_replace("\"true\"", "true", $request->permissions);

      $task = "{";
      foreach ($request->permissions as $key => $value) {
        $task .= "\"".$key."\": ".$value.", ";
      }

      $task = strrev(substr(strrev($task), 2))."}";

      $query = 'UPDATE sw_roles SET permissions = \''.$task.'\' WHERE id = '.$request->id;

      DB::statement($query);

      return redirect()->route('account.role')->with('berhasil', 'value');
    }
}
