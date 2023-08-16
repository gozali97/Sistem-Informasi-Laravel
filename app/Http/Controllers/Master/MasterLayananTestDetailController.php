<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterLayananTestDetailController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/layanantestdetail');
    }

    public function index()
    {
        $data = TarifLab::query()
            ->join('tarif_vars', 'tarif_vars.var_kode', 'tarif_labs.tarif_kelompok')
            ->select('tarif_vars.var_nama', 'tarif_labs.*')
            ->where('tarif_vars.var_seri', 'LAB')
            ->orderBy('tarif_labs.id', 'desc')
            ->get();

        return view('master.layanantestdetail.index', compact('data'));
    }


    public function create()
    {
        $layanan = TarifVar::where("var_seri", "=", "LAB")->get();

        return view('master.layanantestdetail.create', compact('layanan'));
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'detail' => 'required',
                'tipe' => 'required',
                'tarif' => 'required',
                'deskripsi' => 'required',
                'manfaat' => 'required',
                'catatan' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048'
            ], [
                'nama.required' => 'Kolom Kelompok harus diisi.',
                'detail.required' => 'Kolom Nama Layanan harus diisi.',
                'tipe.required' => 'Kolom Tipe harus diisi.',
                'tarif.required' => 'Kolom Tarif harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'manfaat.required' => 'Kolom Manfaat harus diisi.',
                'catatan.required' => 'Kolom Catatan harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $imageName = '/images/layanantestdetail/' . time() . 'layanandetail' . '.' . $request->gambar->extension();
            $path = $request->file('gambar')->move('images/layanantestdetail', $imageName);

            $latestCode = TarifLab::latest('tarif_kode')->first();
            $newCode = $latestCode->tarif_kode + 1;

            $data = new TarifLab;
            $data->tarif_kode = $newCode;
            $data->tarif_kelompok = $request->nama;
            $data->tarif_nama = $request->detail;
            $data->tarif_tipe = $request->tipe;
            $data->tarif_jalan = $request->tarif;
            $data->id_client = 'H002';
            $data->path_gambar = $imageName;
            $data->deskripsi = $request->deskripsi;
            $data->manfaat = $request->manfaat;
            $data->catatan = $request->catatan;
            $data->tarif_status = $request->status;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('layanantestdetail.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layanantestdetail: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data = TarifLab::find($id);

        $layanan = TarifVar::where("var_seri", "=", "LAB")->get();

        return view('master.layanantestdetail.edit', compact('data', 'layanan'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'detail' => 'required',
                'tipe' => 'required',
                'tarif' => 'required',
                'deskripsi' => 'required',
                'manfaat' => 'required',
                'catatan' => 'required',
            ], [
                'nama.required' => 'Kolom Kelompok harus diisi.',
                'detail.required' => 'Kolom Nama Layanan harus diisi.',
                'tipe.required' => 'Kolom Tipe harus diisi.',
                'tarif.required' => 'Kolom Tarif harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'manfaat.required' => 'Kolom Manfaat harus diisi.',
                'catatan.required' => 'Kolom Catatan harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = TarifLab::find($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                $oldImagePath = public_path('images/layanantestdetail/' . str_replace('/images/layanantestdetail/', '', $data->path_gambar));
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = '/images/layanantestdetail/' . time() . 'layanantestdetail' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/layanantestdetail', $imageName);
                $data->path_gambar = $imageName;
            }

            $data->tarif_kelompok = $request->nama;
            $data->tarif_nama = $request->detail;
            $data->tarif_tipe = $request->tipe;
            $data->tarif_jalan = $request->tarif;
            $data->id_client = 'H002';
            $data->deskripsi = $request->deskripsi;
            $data->manfaat = $request->manfaat;
            $data->catatan = $request->catatan;
            $data->tarif_status = $request->status;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('layanantestdetail.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layanantestdetail: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = TarifLab::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/layanantestdetail' . str_replace('/images/layanantestdetail/', '', $data->path_gambar));
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
