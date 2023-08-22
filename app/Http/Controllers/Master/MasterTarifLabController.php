<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketHubung;
use App\Models\PaketLab;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterTarifLabController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/tariflab');
    }

    public function index()
    {

        $tarif = TarifVar::query()->where('var_seri', '=', 'LAB')->orderby('var_nama')->get();

        $paket = PaketLab::all();

        return view('master.tariflab.index', compact('tarif', 'paket'));
    }

    public function create()
    {
        $tarif = TarifVar::where('var_seri', 'LAB')->get();
        $paket = PaketLab::all();
        return view('master.tariflab.create', compact('tarif', 'paket'));
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'tarif_kelompok' => 'required',
                'tarif_nama' => 'required',
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:5048',
                'deskripsi' => 'required',
                'manfaat' => 'required',
                'catatan' => 'required',
                'tarif_status' => 'required',
                'tarif_jalan' => 'required',
            ], [
                'tarif_kelompok.required' => 'Kolom Kategori harus diisi.',
                'tarif_nama.required' => 'Kolom Nama Test harus diisi.',
                'tarif_jalan.required' => 'Kolom Tarif harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'manfaat.required' => 'Kolom Manfaat harus diisi.',
                'catatan.required' => 'Kolom Manfaat harus diisi.',
                'tarif_status.required' => 'Kolom Status harus diisi.',
                'gambar.required' => 'Kolom Gambar harus diupload.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $imageName = '/images/tarif/' . time() . 'tarif' . '.' . $request->gambar->extension();
            $path = $request->file('gambar')->move('images/tarif', $imageName);

            $getlastnumber = TarifLab::select(DB::raw('substring(tarif_kode,3,4) '))
                ->where('tarif_kelompok', '=', $request->tarif_kelompok)
                ->orderBy('tarif_kode', 'DESC')
                ->first();

            if ($getlastnumber == null) {
                $nomorid = 0;
            } else {
                $nomorid = (int)$getlastnumber['substring'];
            }

            $numbertarifkode = $nomorid + 1;
            $tarifkode = sprintf("%02s", $request->tarif_kelompok) . '' . sprintf("%04s", $numbertarifkode);

            $data = new TarifLab;
            $data->tarif_kode = $tarifkode;
            $data->tarif_nama = $request->tarif_nama;
            $data->tarif_jalan = $request->tarif_jalan;
            $data->tarif_kelompok = $request->tarif_kelompok;
            $data->deskripsi = $request->deskripsi;
            $data->manfaat = $request->manfaat;
            $data->catatan = $request->catatan;
            $data->path_gambar = $imageName;
            $data->tarif_status = $request->tarif_status;
            $data->id_client = 'H002';

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('tariflab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating tariflab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = TarifLab::find($id);
        $tarif = TarifVar::where('var_seri', 'LAB')->get();
        $paket = PaketLab::all();

        return view('master.tariflab.edit', compact('data', 'tarif', 'paket'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'tarif_kelompok' => 'required',
                'tarif_nama' => 'required',
                'deskripsi' => 'required',
                'manfaat' => 'required',
                'catatan' => 'required',
                'tarif_status' => 'required',
                'tarif_jalan' => 'required',
            ], [
                'tarif_kelompok.required' => 'Kolom Kategori harus diisi.',
                'tarif_nama.required' => 'Kolom Nama Test harus diisi.',
                'tarif_jalan.required' => 'Kolom Tarif harus diisi.',
                'deskripsi.required' => 'Kolom Deskripsi harus diisi.',
                'manfaat.required' => 'Kolom Manfaat harus diisi.',
                'catatan.required' => 'Kolom Manfaat harus diisi.',
                'tarif_status.required' => 'Kolom Status harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            $data = TarifLab::find($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama

                $oldImagePath = public_path('images/tarif/' . str_replace('/images/tarif/', '', $data->path_gambar));
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Simpan gambar baru
                $imageName = '/images/tarif/' . time() . 'tarif' . '.' . $request->gambar->extension();
                $path = $request->file('gambar')->move('images/tarif', $imageName);
                $data->path_gambar = $imageName;
            }


            $data->tarif_nama = $request->tarif_nama;
            $data->tarif_jalan = $request->tarif_jalan;
            $data->tarif_kelompok = $request->tarif_kelompok;
            $data->deskripsi = $request->deskripsi;
            $data->manfaat = $request->manfaat;
            $data->catatan = $request->catatan;
            $data->tarif_status = $request->tarif_status;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('tariflab.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating tariflab: ' . $e->getMessage());
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

        $gambarPath = public_path('images/tarif' . str_replace('/images/tarif/', '', $data->path_gambar));
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

    public function getData(Request $request)
    {
        $tarif = $request->tarif;
        $paket = $request->paket;

        $data = TarifLab::select('tarif_labs.*')
            ->leftJoin('paket_hubungs', 'paket_hubungs.tarif_kode', 'tarif_labs.tarif_kode')
            ->leftJoin('paket_labs', 'paket_labs.paket_kode', 'paket_hubungs.paket_kode')
            ->orderby('id', 'ASC')
            ->distinct();

        if (!empty($tarif)) {
            $data = $data->where('tarif_labs.tarif_kelompok', 'ilike', '%' . $tarif . '%');
        }

        if (!empty($paket)) {
            $data = $data->where('paket_labs.paket_kode', 'ilike', '%' . $paket . '%');
        }

        $data = $data->get();

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('path_gambar', function ($data) {
                if ($data->path_gambar) {
                    return '<img src = "{{ url($d->path_gambar) }}" alt class="w-px-50 h-auto rounded" />';
                } else {
                    return '<img src = "https://fakeimg.pl/300x200/071952/FFF/?text=Sample&font=lobster" alt class="w-px-50 h-auto rounded" />';
                }
            })
            ->addColumn('status', function ($data) {
                $status = 'Tidak Aktif';

                if ($data->tarif_status == 'A') {
                    $status = 'Aktif';
                }
                return $status;
            })
            ->addColumn('aksi', function ($data) {
                return '<div class="btn-group">
                                    <a href="' . route('tariflab.edit', $data->id) . '" type="button"
                                       class="btn btn-icon btn-outline-warning">
                                        <span class="tf-icons bx bx-edit"></span>
                                    </a>
                                    <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete(' . $data->id . ');">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>';
            })
            ->rawColumns(['path_gambar', 'aksi', 'status'])
            ->make(true);
    }
}
