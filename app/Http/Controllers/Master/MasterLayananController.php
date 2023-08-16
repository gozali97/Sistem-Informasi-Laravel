<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Admvar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterLayananController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:read master/layananhilab');
    }

    public function index()
    {
        $data = Admvar::query()->where('var_seri', 'Layanan')->get();

        return view('master.layananhilab.index', compact('data'));
    }

    public function create()
    {
        return view('master.layananhilab.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'keterangan' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama Layanan harus diisi.',
                'keterangan.required' => 'Kolom Keterangan harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $number = Admvar::query()->where('var_seri', 'Layanan')->get();

            $autonumber = $number->count() + 1;

            $data = new Admvar;
            $data->var_seri         = 'Layanan';
            $data->var_kode          = $autonumber;
            $data->var_nama         = $request->nama;
            $data->var_panjang      = '100';
            $data->keterangan       = $request->keterangan;
            $data->status           = $request->status;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('layananhilab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layananhilab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = Admvar::find($id);

        return view('master.layananhilab.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'keterangan' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama Layanan harus diisi.',
                'keterangan.required' => 'Kolom Keterangan harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            $data = Admvar::find($id);

            $data->var_nama         = $request->nama;
            $data->keterangan       = $request->keterangan;
            $data->status           = $request->status;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('layananhilab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layananhilab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = Admvar::find($id);

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
