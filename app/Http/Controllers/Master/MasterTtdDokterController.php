<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\TtdDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterTtdDokterController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/ttddokter');
    }

    public function index()
    {
        $data = TtdDokter::query()
            ->join('dokters', 'dokters.dokter_kode', 'ttd_dokters.dokter_id')
            ->select('dokters.dokter_nama', 'ttd_dokters.*')
            ->get();

        return view('master.ttddokter.index', compact('data'));
    }


    public function create()
    {
        $dokter = Dokter::query()->where('dokter_status', 'A')->get();
        return view('master.ttddokter.create', compact('dokter'));
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'dokter' => 'required',
                'keterangan' => 'required',
                'signed' => 'required',
                'status' => 'required',
            ], [
                'dokter.required' => 'Kolom judul harus diisi.',
                'keterangan.required' => 'Kolom Gambar harus diupload.',
                'signed.required' => 'Kolom TTD harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $folderPath = public_path('images/ttd/');
            $image_parts = explode(";base64,", $request->signed);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $signature = uniqid() . '.' . $image_type;
            $file = $folderPath . $signature;

            file_put_contents($file, $image_base64);


            $data = new TtdDokter;
            $data->dokter_id = $request->dokter;
            $data->ttd = $signature;
            $data->keterangan = $request->keterangan;
            $data->status = $request->status;
            $data->create_by = Auth::user()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('ttddokter.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating ttddokter: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = TtdDokter::find($id);

        $dokter = Dokter::query()->where('dokter_status', 'A')->get();

        return view('master.ttddokter.edit', compact('dokter', 'data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'dokter' => 'required',
                'keterangan' => 'required',
                'signed' => 'required',
                'status' => 'required',
            ], [
                'dokter.required' => 'Kolom judul harus diisi.',
                'keterangan.required' => 'Kolom Gambar harus diupload.',
                'signed.required' => 'Kolom TTD harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            $data = TtdDokter::find($id);

            if ($request->signed == null) {
                $data->ttd = $data->ttd;
            } else {

                $oldImagePath = public_path('images/ttd/' . $data->ttd);
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $folderPath = public_path('images/ttd/');
                $image_parts = explode(";base64,", $request->signed);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $signature = uniqid() . '.' . $image_type;
                $file = $folderPath . $signature;

                file_put_contents($file, $image_base64);
                $data->ttd = $signature;
            }


            $data->dokter_id = $request->dokter;
            $data->ttd = $signature;
            $data->keterangan = $request->keterangan;
            $data->status = $request->status;
            $data->update_by = Auth::user()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('ttddokter.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating ttddokter: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = TtdDokter::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $oldImagePath = public_path('images/ttd/' . $data->ttd);
        if (is_file($oldImagePath)) {
            unlink($oldImagePath);
        }


        if ($data->delete()) {
            Session::flash('toast_success', 'Data berhasil dihapus');
        } else {
            Session::flash('toast_failed', 'Data gagal dihapus');
        }
        return redirect()->back();
    }
}
