<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Admvar;
use App\Models\UserMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterUserMobileController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read account/usermobile');
    }

    public function index(Request $request)
    {
        $data = UserMobile::query()->select('user_mobiles.*')->orderBy('id', 'ASC')->get();

        $agama = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'AGAMA')
            ->get();

        return view('account.usermobile.index', compact('data', 'agama'));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
                'nama_lengkap' => 'required',
                'no_ktp' => 'required|min:16|max:17',
                'email' => 'required',
                'telepon' => 'required|min:11|max:13',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'domisili' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            date_default_timezone_set('Asia/Jakarta');
            $date = date('Y-m-d H:i:s');

            $user = UserMobile::find($id);
            $user->nama_lengkap = $request->nama_lengkap;
            $user->no_ktp = $request->no_ktp;
            $user->email = $request->email;
            $user->telepon = $request->telepon;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->alamat = $request->alamat;
            $user->domisili = $request->domisili;
            $user->tempat_lahir = $request->tempat_lahir;
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->agama = $request->agama;
            $user->status_user = $request->status_user;
            $user->update_date = $date;
            $user->update_by = Auth::user()->username;

            if ($user->save()) {
                Session::flash('toast_success', 'Data berhasil ditambah');
                return redirect()->route('account.usermobile.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = UserMobile::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        if ($data->delete()) {
            Session::flash('toast_success', 'Data berhasil dihapus');
        } else {
            Session::flash('toast_failed', 'Data gagal dihapus');
        }
        return redirect()->back();
    }
}
