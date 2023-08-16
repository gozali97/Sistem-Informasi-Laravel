<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AlamatHilab;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterLokasiHilabController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/lokasihilab');
    }

    public function index()
    {
        $data = AlamatHilab::all();

        return view('master.lokasihilab.index', compact('data'));
    }


    public function create()
    {
        $provinsi = Provinsi::query()->where('status', '1')->get();
        return view('master.lokasihilab.create', compact('provinsi'));
    }

    public function getKota(Request $request)
    {
        $prov_id = $request->prov_id;

        $data = Kota::query()
            ->where('prov_id', '=', $prov_id)
            ->select('city_name', 'city_id')
            ->orderBy('city_id', 'ASC')
            ->get();

        return response()->json($data);
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'provinsi' => 'required',
                'alamat' => 'required',
                'kota' => 'required',
                'longitude' => 'required',
                'latitude' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama Lokasi harus diisi.',
                'provinsi.required' => 'Kolom Provinsi harus diisi.',
                'alamat.required' => 'Kolom Alamat harus diisi.',
                'kota.required' => 'Kolom Kota harus diisi.',
                'longitude.required' => 'Kolom Longitude harus diisi.',
                'latitude.required' => 'Kolom Latitude harus diisi.',
                'status.required' => 'Kolom Status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $provinsi = Provinsi::query()->where('prov_id', $request->provinsi)->value('prov_name');
            $kota = Kota::query()->where('city_id', $request->kota)->value('city_name');


            $data = new AlamatHilab;
            $data->nama_lokasi = $request->nama;
            $data->provinsi = $provinsi;
            $data->alamat = $request->alamat;
            $data->kota = $kota;
            $data->latitude = $request->latitude;
            $data->longitude = $request->longitude;
            $data->status = $request->status;
            $data->create_by = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('lokasihilab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating lokasihilab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = AlamatHilab::where('lokasi_id', $id)->first();

        $provinsi = Provinsi::query()->where('status', '1')->get();
        $kota = Kota::all();

        return view('master.lokasihilab.edit', compact('data', 'provinsi', 'kota'));
    }


    public function update(Request $request, $id)
    {
        try {

            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'provinsi' => 'required',
                'alamat' => 'required',
                'kota' => 'required',
                'longitude' => 'required',
                'latitude' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama Lokasi harus diisi.',
                'provinsi.required' => 'Kolom Provinsi harus diisi.',
                'alamat.required' => 'Kolom Alamat harus diisi.',
                'kota.required' => 'Kolom Kota harus diisi.',
                'longitude.required' => 'Kolom Longitude harus diisi.',
                'latitude.required' => 'Kolom Latitude harus diisi.',
                'status.required' => 'Kolom Status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = AlamatHilab::where('lokasi_id', $id)->first();

            $provinsi = Provinsi::query()->where('prov_id', $request->provinsi)->value('prov_name');
            $kota = Kota::query()->where('city_id', $request->kota)->value('city_name');


            $data->nama_lokasi = $request->nama;
            $data->provinsi = $provinsi;
            $data->alamat = $request->alamat;
            $data->kota = $kota;
            $data->latitude = $request->latitude;
            $data->longitude = $request->longitude;
            $data->status = $request->status;
            $data->update_by = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('lokasihilab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating lokasihilab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = AlamatHilab::where('lokasi_id', $id)->first();

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
