<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\SyaratKetentuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterSyaratKetentuanController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/syaratketentuan');
    }

    public function index()
    {
        $data = SyaratKetentuan::all();

        return view('master.syaratketentuan.index', compact('data'));
    }


    public function create()
    {
        return view('master.syaratketentuan.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'konten' => 'required',
                'kategori' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'konten.required' => 'Kolom konten harus diisi.',
                'kategori.required' => 'Kolom kategori harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diupload.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $imageName = '/images/syaratketentuan/' . time() . 'syaratketentuan' . '.' . $request->gambar->extension();
            $path = $request->file('gambar')->move('images/syaratketentuan', $imageName);


            $data = new SyaratKetentuan();
            $data->judul_syarat_ketentuan = $request->judul;
            $data->syarat_ketentuan_value = $request->konten;
            $data->kategori_nama = $request->kategori;
            $data->path_gambar = $imageName;
            $data->status = $request->status;
            $data->create_by = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('syaratketentuan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating syaratketentuan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = SyaratKetentuan::find($id);

        return view('master.syaratketentuan.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'konten' => 'required',
                'kategori' => 'required',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'konten.required' => 'Kolom konten harus diisi.',
                'kategori.required' => 'Kolom kategori harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = SyaratKetentuan::find($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                $oldImagePath = public_path('images/syaratketentuan/' . str_replace('/images/syaratketentuan/', '', $data->path_gambar));
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = '/images/syaratketentuan/' . time() . 'syaratketentuan' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/syaratketentuan', $imageName);
                $data->path_gambar = $imageName;
            }

            $data->judul_syarat_ketentuan = $request->judul;
            $data->syarat_ketentuan_value = $request->konten;
            $data->kategori_nama = $request->kategori;
            $data->status = $request->status;
            $data->create_by = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('syaratketentuan.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating syaratketentuan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = SyaratKetentuan::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/syaratketentuan' . str_replace('/images/syaratketentuan/', '', $data->path_gambar));
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
