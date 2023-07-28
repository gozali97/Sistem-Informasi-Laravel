<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\BarcodePemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterBarcodePemeriksaanController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/barcodepemeriksaan');
    }

    public function index(Request $request)
    {
        $data = BarcodePemeriksaan::all();

        return view('master.barcodepemeriksaan.index', compact('data'));
    }


    public function create()
    {
        return view('master.barcodepemeriksaan.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'sign' => 'required',
                'instrument' => 'required',
                'copy' => 'required',
            ], [
                'nama.required' => 'Kolom Nama harus diisi.',
                'sign.required' => 'Kolom Sign harus diisi.',
                'instrument.required' => 'Kolom Instrument harus diisi.',
                'copy.required' => 'Kolom Copy harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            $data = new BarcodePemeriksaan;
            $data->nama    = $request->nama;
            $data->sign   = $request->sign;
            $data->inst = $request->instrument;
            $data->kopi    = $request->copy;
            $data->user_id = Auth::User()->username;
            $data->use_date = date('Y-m-d H:i:s');
            $data->created_at = date('Y-m-d H:i:s');
            $data->id_client     = 'H002';
            $data->save();

            $lastId = $data->id;

            $kode = sprintf('%02d', $lastId);

            $data->kode = $kode;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('barcodepemeriksaan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating barcodepemeriksaan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data = BarcodePemeriksaan::find($id);

        return view('master.barcodepemeriksaan.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'sign' => 'required',
                'instrument' => 'required',
                'copy' => 'required',
            ], [
                'nama.required' => 'Kolom Nama harus diisi.',
                'sign.required' => 'Kolom Sign harus diisi.',
                'instrument.required' => 'Kolom Instrument harus diisi.',
                'copy.required' => 'Kolom Copy harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            $data = BarcodePemeriksaan::find($id);
            $data->nama    = $request->nama;
            $data->sign   = $request->sign;
            $data->inst = $request->instrument;
            $data->kopi    = $request->copy;
            $data->user_id = Auth::User()->username;
            $data->use_date = date('Y-m-d H:i:s');
            $data->updated_at = date('Y-m-d H:i:s');

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('barcodepemeriksaan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating barcodepemeriksaan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $data = BarcodePemeriksaan::find($id);

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
