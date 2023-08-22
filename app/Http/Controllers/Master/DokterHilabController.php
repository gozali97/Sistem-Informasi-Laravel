<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Kota;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DokterHilabController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/dokterhilab');
    }

    public function index(Request $request)
    {
        $data = Dokter::query()
            ->join('units', 'units.unit_kode', 'dokters.unit_kode')
            ->select('dokters.*', 'units.unit_nama')
            ->orderBy('dokter_kode', 'desc')
            ->get();

        return view('master.dokterhilab.index', compact('data'));
    }

    public function create()
    {
        $unit = Unit::query()->select('*')
            ->orderBy('unit_kode', 'desc')
            ->get();

        $getLastnumber = Dokter::select(DB::raw('substring(dokter_kode,3,4)'))
            ->orderBy('dokter_kode', 'DESC')
            ->first();

        if ($getLastnumber == null) {
            $nomorid = 0;
        } else {
            $nomorid = (int)$getLastnumber['substring'];
        }

        $new_number = $nomorid + 1;
        $kode = '32' . sprintf("%04s", $new_number);
        $kota = Kota::all();

        return view('master.dokterhilab.create', compact('unit', 'kode', 'kota'));
    }

    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama_dokter' => 'required',
                'nama_lengkap' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'dokter_tarif' => 'required',
                'status' => 'required',
            ], [
                'nama_dokter.required' => 'Kolom nama dokter harus diisi.',
                'nama_lengkap.required' => 'Kolom nama lengkap harus diisi.',
                'alamat.required' => 'Kolom alamat harus diisi.',
                'no_hp.required' => 'Kolom no telepone harus diupload.',
                'dokter_tarif.required' => 'Kolom tarif dokter harus diupload.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = new Dokter;
            $data->dokter_kode = $request->dokter_kode;
            $data->dokter_nama = $request->nama_dokter;
            $data->dokter_nama_lengkap = $request->nama_lengkap;
            $data->dokter_alamat = $request->alamat;
            $data->dokter_kota = $request->kota;
            $data->dokter_telepon = $request->no_hp;
            $data->dokter_status = $request->status;
            $data->unit_kode = 32;
            $data->dokter_tarif = $request->dokter_tarif;
            $data->prsh_kode = '1-0001';
            $data->id_client = Auth::User()->idlab;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('dokterhilab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating dokterhilab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = Dokter::where('dokter_kode', $id)->first();

        $unit = Unit::query()->select('*')
            ->orderBy('unit_kode', 'desc')
            ->get();

        $kota = Kota::all();

        return view('master.dokterhilab.edit', compact('data', 'unit', 'kota'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama_dokter' => 'required',
                'nama_lengkap' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'dokter_tarif' => 'required',
                'status' => 'required',
            ], [
                'nama_dokter.required' => 'Kolom nama dokter harus diisi.',
                'nama_lengkap.required' => 'Kolom nama lengkap harus diisi.',
                'alamat.required' => 'Kolom alamat harus diisi.',
                'no_hp.required' => 'Kolom no telepone harus diupload.',
                'dokter_tarif.required' => 'Kolom tarif dokter harus diupload.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = Dokter::where('dokter_kode', $id)->first();
            $data->dokter_nama = $request->nama_dokter;
            $data->dokter_nama_lengkap = $request->nama_lengkap;
            $data->dokter_alamat = $request->alamat;
            $data->dokter_kota = $request->kota;
            $data->dokter_telepon = $request->no_hp;
            $data->dokter_status = $request->status;
            $data->dokter_tarif = $request->dokter_tarif;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('dokterhilab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating dokterhilab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = Dokter::where('dokter_kode', $id)->first();

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
