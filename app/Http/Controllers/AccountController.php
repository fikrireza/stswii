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
use Image;

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
          'name.required' => 'This field is required',
          'email.required' => 'This field is required',
          'email.email' => 'Format email not supported',
          'email.unique' => 'Email has already taken',
          'avatar.image' => 'Format not supported',
          'avatar.max' => 'File Size Too Big',
        ];

        $validator = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|email|unique:sw_users',
          'avatar' => 'image|mimes:jpeg,bmp,png|max:1000'
        ], $message);

        if($validator->fails())
        {
          return redirect()->route('account.tambah')->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
          $confirmation_code = str_random(30).time();
          $userSave = User::create([
            'name' => $request->name,
            'avatar' => 'user.png',
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'confirmed' => 0,
            'login_count' => 0,
            'confirmation_code' => $confirmation_code,
            'api_token' => base64_encode(openssl_random_pseudo_bytes(60)),
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

    public function ubah($id)
    {
      $getUser = User::find($id);

      if(!$getUser){
        abort(404);
      }

      $getRole = Role::get();

      return view('account.ubah', compact('getUser', 'getRole'));
    }

    public function update(Request $request)
    {
      $message = [
        'name.required' => 'This field is required.',
        'email.required' => 'This field is required.',
        'email.email' => 'Format not supported',
        'email.unique' => 'Email has already taken',
        'avatar.image' => 'Format not supported',
        'avatar.max' => 'File Size Too Big',
      ];

      $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:sw_users,email,'.$request->id,
        'avatar' => 'image|mimes:jpeg,bmp,png|max:1000'
      ], $message);

      if($validator->fails())
      {
        return redirect()->route('account.ubah', ['id' => $request->id ])->withErrors($validator)->withInput();
      }

      DB::transaction(function() use($request){
        $image = $request->file('avatar');

        if($request->active == 1){
          $confirmed = 1;
        }else{
          $confirmed = 0;
        }

        $update = User::find($request->id);
        $update->name = $request->name;
        $update->email = $request->email;
        $update->confirmed = $confirmed;
        if($image){
          $img_url = 'web-admin-'.str_slug($request->name,'-').'.'. $image->getClientOriginalExtension();
          Image::make($image)->fit(250,250)->save('amadeo/images/profile/'. $img_url);
          $update->avatar = $img_url;
        }
        $update->update();

        $rolesUpdate = RoleUser::where('user_id', $request->id)->delete();

        foreach ($request->role as $key) {
          $role = RoleUser::firstOrCreate(['user_id' => $request->id, 'role_id' => $key]);
        }
      });


      return redirect()->route('account.index')->with('berhasil', 'User has been successfully updated');

    }

    public function reset($id)
    {
      $getUser = User::find($id);

      if(!$getUser){
        abort(404);
      }

      $getUser->password = Hash::make(12345678);
      $getUser->save();

      if($getUser->id == Auth::user()->id){
          auth()->logout();
      }

      // $data = array([
      //   'name' => $getUser->name,
      //   'email' => $getUser->email,
      //   'password' => 12345678
      // ]);
      //
      // Mail::send('email.reset', ['data' => $data], function($message) use ($data) {
      //   $message->from('administrator@amadeo.id', 'Administrator')
      //           ->to($data[0]['email'], $data[0]['name'])
      //           ->subject('Reset Password Akun Web Admin');
      // });

      return redirect()->route('account.index')->with('berhasil', 'Password user '.$getUser->name.' Has been reset');
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

      $task = "{";
      foreach ($request->permissions as $key => $value) {
        $task .= "\"".$key."\": ".$value.", ";
      }

      $task = strrev(substr(strrev($task), 2))."}";

      $query = 'UPDATE wa_roles SET permissions = \''.$task.'\' WHERE id = '.$request->id;

      DB::statement($query);

      return redirect()->route('account.role')->with('berhasil', 'Data Role has been successfully updated');
    }
}
