<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterBannerController extends Controller
{

    public function __construct()
    {

        $this->middleware('can:read master/banner');
    }

    public function index()
    {
        $data = Banner::all();

        return view('master.banner.index', compact('data'));
    }

    public function create()
    {
        return view('master.banner.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diupload.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $imageName  = '/images/banner/' . time() . 'banner' . '.' . $request->gambar->extension();
            $path       = $request->file('gambar')->move('images/banner', $imageName);


            $data = new Banner;
            $data->judul_banner    = $request->judul;
            $data->path_banner  = $imageName;
            $data->status  = $request->status;
            $data->create_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('banner.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating banner: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = Banner::find($id);

        return view('master.banner.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'judul' => 'required',
                'status' => 'required',
            ], [
                'judul.required' => 'Kolom judul harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = Banner::find($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama

                $oldImagePath = public_path('images/banner/' . str_replace('/images/banner/', '', $data->path_banner));
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = '/images/banner/' . time() . 'banner' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/banner', $imageName);
                $data->path_banner = $imageName;
            }


            $data->judul_banner    = $request->judul;
            $data->status  = $request->status;
            $data->update_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('banner.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating banner: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = Banner::find($id);

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/banner' . str_replace('/images/banner/', '', $data->path_banner));
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
