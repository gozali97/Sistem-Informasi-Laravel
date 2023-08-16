<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TarifVar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterLayananLabController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/layananlab');
    }

    public function index()
    {
        $data = TarifVar::where('var_seri', 'LAB')->get();

        return view('master.layananlab.index', compact('data'));
    }


    public function create()
    {
        return view('master.layananlab.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048',
                'nilai' => 'required',
                'nilai_lama' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama Layanan harus diisi.',
                'gambar.required' => 'Kolom Gambar Layanan harus diisi.',
                'nilai.required' => 'Kolom Nilai harus diisi.',
                'nilai_lama.required' => 'Kolom Nilai lama harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $varseri = 'LAB';

            $lastNumber = TarifVar::where('var_seri', 'LAB')->orderBy('var_kode', 'desc')->first();
            if (!$lastNumber) {
                $nextNumber = 1;
            } else {
                $nextNumber = intval($lastNumber->var_kode) + 1;
            }

            $imageName  = '/images/layananlab/' . time() . 'layananlab' . '.' . $request->gambar->extension();
            $path       = $request->file('gambar')->move('images/layananlab', $imageName);

            $data = new TarifVar;
            $data->path_gambar      = $imageName;
            $data->var_seri          = $varseri;
            $data->var_kode             = $nextNumber;
            $data->var_kelompok      = $varseri;
            $data->var_nama          = $request->nama;
            $data->status           = $request->status;
            $data->var_nilai         = $request->nilai;
            $data->var_nilai_lama     = $request->nilai_lama;
            $data->var_panjang       = 2;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('layananlab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layananlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data = TarifVar::find($id);

        return view('master.layananlab.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'nilai' => 'required',
                'nilai_lama' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom Nama Layanan harus diisi.',
                'nilai.required' => 'Kolom Nilai harus diisi.',
                'nilai_lama.required' => 'Kolom Nilai lama harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $varseri = 'LAB';

            $data = TarifVar::find($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                $oldImagePath = public_path('images/layananlab/' . str_replace('/images/layananlab/', '', $data->path_gambar));
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = '/images/layananlab/' . time() . 'layananlab' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/layananlab', $imageName);
                $data->path_gambar = $imageName;
            }

            $data->var_seri          = $varseri;
            $data->var_kelompok      = $varseri;
            $data->var_nama          = $request->nama;
            $data->status           = $request->status;
            $data->var_nilai         = $request->nilai;
            $data->var_nilai_lama     = $request->nilai_lama;
            $data->var_panjang       = 2;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('layananlab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layananlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = TarifVar::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/layananlab' . str_replace('/images/layananlab/', '', $data->path_gambar));
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
