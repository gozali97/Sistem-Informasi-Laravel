<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\GroupPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterGroupPaketController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/grouppaket');
    }

    public function index()
    {
        $data = GroupPaket::query()->select('group_pakets.*')->orderBy('group_paket_id', 'ASC')->get();

        return view('master.grouppaket.index', compact('data'));
    }


    public function create()
    {
        return view('master.grouppaket.create');
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom nama harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diupload.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $imageName  = '/images/grouppaket/' . time() . 'grouppaket' . '.' . $request->gambar->extension();
            $path       = $request->file('gambar')->move('images/grouppaket', $imageName);

            $lastid = GroupPaket::select('group_paket_id')
                ->orderBy('group_paket_id', 'DESC')
                ->first();
            if ($lastid == null) {
                $noid = 0;
            } else {
                $noid = $lastid['group_paket_id'];
            }
            $paketid = $noid + 1;
            $paketkelompok = sprintf("%02s", $paketid);

            $data = new GroupPaket;
            $data->paket_kelompok    = $paketkelompok;
            $data->nama_group    = $request->nama;
            $data->path_gambar  = $imageName;
            $data->status  = $request->status;
            $data->create_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('grouppaket.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating grouppaket: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }



    public function edit($id)
    {
        $data = GroupPaket::where('group_paket_id', $id)->first();

        return view('master.grouppaket.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'status' => 'required',
            ], [
                'nama.required' => 'Kolom nama harus diisi.',
                'status.required' => 'Kolom status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = GroupPaket::where('group_paket_id', $id)->first();

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama

                $oldImagePath = public_path('images/grouppaket/' . str_replace('/images/grouppaket/', '', $data->path_gambar));
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = '/images/grouppaket/' . time() . 'grouppaket' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/grouppaket', $imageName);
                $data->path_gambar = $imageName;
            }

            $data->nama_group    = $request->nama;
            $data->status  = $request->status;
            $data->update_by   = Auth::User()->username;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('grouppaket.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating grouppaket: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = GroupPaket::where('group_paket_id', $id)->first();

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/grouppaket' . str_replace('/images/grouppaket/', '', $data->path_gambar));
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
