<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:read account/user');
    }

    public function index(Request $request)
    {
        $data = User::query()
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->leftJoin('roles', 'roles.id', 'model_has_roles.role_id')
            ->select('users.*', 'roles.name')
            ->get();

        return view('account.user.index', compact('data'));
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'foto' => 'required',
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $foto  = time() . 'user' . '.' . $request->foto->extension();
            $path       = $request->file('foto')->move('assets/img/profile', $foto);

            User::create([
                'username' => $request->username,
                'first_name' => $request->firstname,
                'last_name' => $request->lastname,
                'foto' => $foto,
                'email' => $request->email,
                'password' => Hash::make('password')
            ]);

            Session::flash('toast_success', 'Data berhasil ditambah');
            return redirect()->route('account.user.index');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $user = User::find($id);

            if ($request->hasFile('foto')) {
                // Hapus gambar lama
                if (file_exists(public_path('assets/img/profile' . $user->foto))) {
                    unlink(public_path('assets/img/profile' . $user->foto));
                }

                // Simpan gambar baru
                $foto = time() . 'user' . '.' . $request->foto->extension();
                $path = $request->file('foto')->move('assets/img/profile', $foto);
                $user->gambar = $foto;
            }


            $user->username = $request->username;
            $user->first_name = $request->firstname;
            $user->last_name = $request->lastname;
            $user->email = $request->email;
            $user->save();

            Session::flash('toast_success', 'Data berhasil ditambah');
            return redirect()->route('account.user.index');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {

        $data = User::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('assets/img/profile/' . $data->foto);
        if (file_exists($gambarPath)) {
            unlink($gambarPath);
        }

        if ($data->delete()) {
            Session::flash('toast_success', 'Data berhasil dihapus');
        } else {
            Session::flash('toast_failed', 'Data gagal dihapus');
        }
        return redirect()->back();
    }
}
