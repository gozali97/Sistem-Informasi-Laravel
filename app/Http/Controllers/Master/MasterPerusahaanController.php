<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterPerusahaanController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:read master/perusahaan');
    }

    public function index()
    {
        $data = Perusahaan::query()
            ->select('perusahaans.*')
            ->where('prsh_kode', 'not like', '0%')
            ->where('prsh_kode', 'not like', '3%')
            ->get();

        return view('master.perusahaan.index', compact('data'));
    }


    public function create()
    {
        $pengirim = Group::query()
            ->where('grup_jenis', 'PRSH')
            ->where(function ($query) {
                $query->where('grup_nama', 'like', 'Perusahaan')
                    ->orWhere('grup_nama', 'like', 'Rumah Sakit');
            })
            ->get();


        return view('master.perusahaan.create', compact('pengirim'));
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama harus diisi.',
                'alamat.required' => 'Kolom Alamat harus diisi.',
                'no_hp.required' => 'Kolom Kontak harus diisi.',
                'status.required' => 'Kolom Status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $code = $request->kode;

            $lastData = Perusahaan::query()
                ->where('prsh_kode', 'like', $code . '-%')
                ->orderBy('prsh_kode', 'desc')->first();

            $lastCodeNumber = 0;

            if ($lastData) {
                preg_match('/-(\d+)/', $lastData->prsh_kode, $matches);
                $lastCodeNumber = (int)$matches[1];
            }

            $data = new Perusahaan;

            $data->prsh_kode = $code . '-' . str_pad(($lastCodeNumber + 1), 4, '0', STR_PAD_LEFT);

            $data->prsh_nama = $request->nama;
            $data->prsh_alamat_1 = $request->alamat;
            $data->prsh_kontak = $request->no_hp;
            $data->prsh_status = $request->status;
            $data->prsh_jenis = $request->kode;
            $data->id_client = 'H002';
            $data->created_at = date('Y-m-d H:i:s');

            if ($data->save()) {
                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('perusahaan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating perusahaan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = Perusahaan::where('prsh_kode', '=', $id)->first();

        $pengirim = Group::query()
            ->where('grup_jenis', 'PRSH')
            ->where(function ($query) {
                $query->where('grup_nama', 'like', 'Perusahaan')
                    ->orWhere('grup_nama', 'like', 'Rumah Sakit');
            })
            ->get();


        return view('master.perusahaan.edit', compact('data', 'pengirim'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama harus diisi.',
                'alamat.required' => 'Kolom Alamat harus diisi.',
                'no_hp.required' => 'Kolom Kontak harus diisi.',
                'status.required' => 'Kolom Status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            $data = Perusahaan::where('prsh_kode', '=', $id)->first();

            $data->prsh_nama = $request->nama;
            $data->prsh_alamat_1 = $request->alamat;
            $data->prsh_kontak = $request->no_hp;
            $data->prsh_status = $request->status;
            $data->prsh_jenis = $request->kode;
            $data->id_client = 'H002';

            if ($data->save()) {
                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('perusahaan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating perusahaan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = Perusahaan::where('prsh_kode', '=', $id)->first();

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
