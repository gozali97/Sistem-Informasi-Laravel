<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\GroupPaket;
use App\Models\PaketLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterPaketLabController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/paketlab');
    }

    public function index(Request $request)
    {
        $data = PaketLab::all();


        return view('master.paketlab.index', compact('data'));
    }

    //return view('errors.505');

    public function create()
    {
        $groups = GroupPaket::query()
            ->select('*')
            ->orderBy('paket_kelompok', 'ASC')
            ->get();

        $getLastnumber = PaketLab::select(DB::raw('substring(paket_kode,3,5)'))
            ->orderBy('paket_kode', 'DESC')
            ->first();
        if ($getLastnumber == null) {
            $nomorid = 0;
        } else {
            $nomorid = (int)$getLastnumber['substring'];
        }
        $new_numberpaketkode = $nomorid + 1;
        $paket_kode = 'PK' . sprintf("%05s", $new_numberpaketkode);

        return view('master.paketlab.create', compact('groups', 'paket_kode'));
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama_paket' => 'required',
                'gambar'     => 'required|image|mimes:png,jpg,jpeg|max:5048',
                'group_paket' => 'required',
                'tarif' => 'required',
                'deskripsi' => 'required',
                'catatan' => 'required',
                'status' => 'required',
            ], [
                'nama_paket.required' => 'Kolom Kategori harus diisi.',
                'tarif.required' => 'Kolom Tarif harus diisi.',
                'group_paket.required' => 'Kolom Group Paket harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'catatan.required' => 'Kolom Catatan harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diupload.',
                'status.required' => 'Kolom Status harus diisi.',
                'manfaat.required' => 'Kolom Manfaat harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = new PaketLab;
            $imageName  = time() . 'paket' . '.' . $request->gambar->extension();
            $path       = $request->file('gambar')->move('images/paket', $imageName);

            $data->paket_kode = $request->paket_kode;
            $data->paket_kelompok = $request->group_paket;
            $data->paket_nama    = $request->nama_paket;
            $data->paket_jalan   = preg_replace('/[Rp. ]/', '', $request->tarif);
            $data->path_gambar  = 'https://staging-lis.k24.co.id/images/paket/' . $imageName;
            $data->paket_utama = 0;
            $data->paket_vip = 0;
            $data->paket_kelas_1 = 0;
            $data->paket_kelas_2 = 0;
            $data->paket_kelas_3 = 0;
            $data->deskripsi    = $request->deskripsi;
            $data->manfaat      = $request->manfaat;
            $data->catatan      = $request->catatan;
            $data->created_at   = date('Y-m-d H:i:s');
            $data->updated_at   = date('Y-m-d H:i:s');
            $data->paket_status = $request->status;
            $data->id_client     = 'H002';
            $data->paket_periode = date('Y-m-d H:i:s');

            $data->save();

            Session::flash('toast_success', 'Data berhasil ditambahkan');
            return redirect()->route('paketlab.index');
        } catch (\Exception $e) {
            Log::error('Error creating paketlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
    }

    public function edit($id)
    {
        $data = PaketLab::where('paket_kode', $id)->first();

        $groups = GroupPaket::query()
            ->select('*')
            ->orderBy('paket_kelompok', 'ASC')
            ->get();

        $getLastnumber = PaketLab::select(DB::raw('substring(paket_kode,3,5)'))
            ->orderBy('paket_kode', 'DESC')
            ->first();
        if ($getLastnumber == null) {
            $nomorid = 0;
        } else {
            $nomorid = (int)$getLastnumber['substring'];
        }
        $new_numberpaketkode = $nomorid + 1;
        $paket_kode = 'PK' . sprintf("%05s", $new_numberpaketkode);

        return view('master.paketlab.edit', compact('data', 'groups', 'paket_kode'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama_paket' => 'required',
                'group_paket' => 'required',
                'tarif' => 'required',
                'deskripsi' => 'required',
                'catatan' => 'required',
                'status' => 'required',
            ], [
                'nama_paket.required' => 'Kolom Kategori harus diisi.',
                'tarif.required' => 'Kolom Tarif harus diisi.',
                'group_paket.required' => 'Kolom Group Paket harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'catatan.required' => 'Kolom Catatan harus diisi.',
                'status.required' => 'Kolom Status harus diisi.',
                'manfaat.required' => 'Kolom Manfaat harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = PaketLab::where('paket_kode', $id)->first();

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                if (file_exists(public_path('images/paket' . $data->path_gambar))) {
                    unlink(public_path('images/paket' . $data->path_gambar));
                }

                // Simpan gambar baru
                $imageName = time() . 'paket' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/paket', $imageName);
                $data->path_gambar = 'https://staging-lis.k24.co.id/images/paket/' . $imageName;
            }

            $data->paket_kode = $request->paket_kode;
            $data->paket_kelompok = $request->group_paket;
            $data->paket_nama    = $request->nama_paket;
            $data->paket_jalan   = preg_replace('/[Rp. ]/', '', $request->tarif);
            $data->paket_utama = 0;
            $data->paket_vip = 0;
            $data->paket_kelas_1 = 0;
            $data->paket_kelas_2 = 0;
            $data->paket_kelas_3 = 0;
            $data->deskripsi    = $request->deskripsi;
            $data->manfaat      = $request->manfaat;
            $data->catatan      = $request->catatan;
            $data->created_at   = date('Y-m-d H:i:s');
            $data->updated_at   = date('Y-m-d H:i:s');
            $data->paket_status = $request->status;
            $data->id_client     = 'H002';
            $data->paket_periode = date('Y-m-d H:i:s');

            $data->save();

            Session::flash('toast_success', 'Data berhasil diperbarui');
            return redirect()->route('paketlab.index');
        } catch (\Exception $e) {
            Log::error('Error creating paketlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = PaketLab::where('paket_kode', $id)->first();

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $gambarPath = public_path('images/paket' . $data->path_gambar);
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
