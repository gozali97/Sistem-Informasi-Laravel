<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterInformasiController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/informasi');
    }

    public function index()
    {
        $data = Informasi::all();

        return view('master.informasi.index', compact('data'));
    }


    public function create()
    {
        return view('master.informasi.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'url' => 'required',
                'informasi' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'url.required' => 'Kolom url harus diisi.',
                'informasi.required' => 'Kolom informasi harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diupload.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $imageName  = '/images/informasi/' . time() . 'informasi' . '.' . $request->gambar->extension();
            $path       = $request->file('gambar')->move('images/informasi', $imageName);


            $data = new Informasi;
            $data->judul_informasi    = $request->judul;
            $data->informasi    = $request->informasi;
            $data->alamat_url    = $request->url;
            $data->path_gambar  = $imageName;
            $data->status  = $request->status;
            $data->create_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('informasi.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating informasi: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = Informasi::find($id);
        return view('master.informasi.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'url' => 'required',
                'informasi' => 'required',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'url.required' => 'Kolom judul harus diisi.',
                'informasi.required' => 'Kolom judul harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = Informasi::find($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                $oldImagePath = public_path('images/informasi/' . str_replace('/images/informasi/', '', $data->path_gambar));
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = '/images/informasi/' . time() . 'informasi' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/informasi', $imageName);
                $data->path_gambar = $imageName;
            }



            $data->judul_informasi    = $request->judul;
            $data->informasi    = $request->informasi;
            $data->alamat_url    = $request->url;
            $data->status  = $request->status;
            $data->create_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('informasi.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating informasi: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = Informasi::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/informasi'  . str_replace('/images/informasi/', '', $data->path_gambar));
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
