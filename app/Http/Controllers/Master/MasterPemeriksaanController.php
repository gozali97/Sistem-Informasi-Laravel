<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterPemeriksaanController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:read master/pemeriksaan');
    }

    public function index(Request $request)
    {
        $data = Group::query()->where('grup_jenis', 'PRSH')->get();


        return view('master.pemeriksaan.index', compact('data'));
    }


    public function create()
    {
        return view('master.pemeriksaan.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'grup_nama' => 'required',
                'deskripsi' => 'required',
                'status' => 'required',
            ], [
                'grup_nama.required' => 'Kolom Nama Pemeriksaan harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'status.required' => 'Kolom Status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            $number = Group::query()->where('grup_jenis', 'PRSH')->get();
            $autonumber = $number->count() + 1;

            $data = new Group;

            $data->grup_jenis         = 'PRSH';
            $data->grup_kode          = $autonumber;
            $data->grup_nama         = $request->grup_nama;
            $data->deskripsi       = $request->deskripsi;
            $data->status           = $request->status;
            $data->id_client           = 'H002';
            $data->created_at     = date('Y-m-d H:i:s');
            $data->updated_at      = date('Y-m-d H:i:s');

            if ($data->save()) {
                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('pemeriksaan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating pemeriksaan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = Group::where('id', $id)->first();

        return view('master.pemeriksaan.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'grup_nama' => 'required',
                'deskripsi' => 'required',
                'status' => 'required',
            ], [
                'grup_nama.required' => 'Kolom Nama Pemeriksaan harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'status.required' => 'Kolom Status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = Group::where('id', $id)->first();

            $data->grup_nama         = $request->grup_nama;
            $data->deskripsi       = $request->deskripsi;
            $data->status           = $request->status;
            $data->updated_at      = date('Y-m-d H:i:s');

            if ($data->save()) {
                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('pemeriksaan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating pemeriksaan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = Group::where('id', $id)->first();

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
