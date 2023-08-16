<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TentangKami;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterTentangKamiController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/tentangkami');
    }

    public function index()
    {
        $data = TentangKami::all();

        return view('master.tentangkami.index', compact('data'));
    }

    public function create()
    {
        return view('master.tentangkami.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'deskripsi' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'deskripsi.required' => 'Kolom deskripsi harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diupload.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $imageName  = time() . 'tentangkami' . '.' . $request->gambar->extension();
            $path       = $request->file('gambar')->move('images/tentangkami', $imageName);


            $data = new TentangKami;
            $data->judul    = $request->judul;
            $data->deskripsi    = $request->deskripsi;
            $data->gambar  = $imageName;
            $data->status  = $request->status;
            $data->create_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('tentangkami.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating tentangkami: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = TentangKami::find($id);

        return view('master.tentangkami.edit', compact('data'));
    }



    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'deskripsi' => 'required',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'deskripsi.required' => 'Kolom deskripsi harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = TentangKami::find($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                $oldImagePath = public_path('images/tentangkami/' . $data->gambar);
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = time() . 'tentangkami' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/tentangkami', $imageName);
                $data->gambar = $imageName;
            }


            $data->judul    = $request->judul;
            $data->deskripsi    = $request->deskripsi;
            $data->status  = $request->status;
            $data->update_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('tentangkami.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating tentangkami: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $data = TentangKami::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/tentangkami' . $data->gambar);
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
