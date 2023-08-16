<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterLayananPromoController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/layananpromo');
    }

    public function index(Request $request)
    {

        $tarif = $request->tarif;
        $paket = $request->paket;

        $data = TarifLab::select('tarif_labs.*');
        if (!empty($tarif)) {
            $data = $data->where('tarif_labs.tarif_kelompok', 'like', '%' . $tarif . '%');
        }

        $data = $data->orderBy('id', 'desc')->get();

        $tarif = TarifVar::query()->where('var_seri', '=', 'LAB')->orderby('var_nama')->get();

        return view('master.layananpromo.index', compact('data', 'tarif'));
    }


    public function create()
    {
        $tarif = TarifVar::where('var_seri', 'LAB')->get();
        $lab = TarifLab::all();

        return view('master.layananpromo.create', compact('tarif', 'lab'));
    }

    public function getData(Request $request)
    {
        $var_kode = $request->var_kode;
        $lab = TarifLab::query()
            ->where('tarif_kelompok', $var_kode)
            ->orderBy('tarif_kode', 'ASC')
            ->get();
        return response()->json($lab);
    }

    public function getDataByKode($tarifKode)
    {
        $data = TarifLab::where('tarif_kode', $tarifKode)->first();
        return response()->json($data);
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'tarif_kode' => 'required',
                'tarif_kelompok' => 'required',
                'tarif_jalan' => 'required|numeric',
                'tarif_promo' => 'required',
                'persen_promo' => 'required',
                'harga_akhir' => 'required',
                'promo_start' => 'required',
                'promo_end' => 'required',
                'status' => 'required'
            ], [
                'tarif_kelompok.required' => 'Kolom Kategori harus diisi.',
                'tarif_kode.required' => 'Kolom Nama Layanan harus diisi.',
                'tarif_jalan.required' => 'Kolom Tarif Layanan harus diisi.',
                'tarif_promo.required' => 'Kolom Jumlah Promo harus diisi.',
                'persen_promo.required' => 'Kolom % Promo harus diisi.',
                'harga_akhir.required' => 'Kolom harga akhir harus diisi.',
                'promo_start.required' => 'Kolom Tanggal Mulai harus diisi.',
                'promo_end.required' => 'Kolom Tanggal Selesai harus diisi.',
                'status.required' => 'Kolom Status Promo harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = TarifLab::updateOrCreate(
                [
                    'tarif_kode' => $request->tarif_kode,
                    'tarif_kelompok' => $request->tarif_kelompok,
                ],
                [

                    'tarif_jalan' => $request->tarif_jalan,
                    'promo_value' => str_replace('.', '', $request->tarif_promo),
                    'promo_percent' => $request->persen_promo,
                    'fix_value' => str_replace('.', '', $request->harga_akhir),
                    'periode_start' => $request->promo_start,
                    'periode_end' => $request->promo_end,
                    'status_promo' => $request->status,
                ]
            );

            if ($data) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('layananpromo.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layananpromo: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = TarifLab::find($id);

        $tarif = TarifVar::where('var_seri', 'LAB')->get();
        $lab = TarifLab::all();

        return view('master.layananpromo.edit', compact('data', 'tarif', 'lab'));
    }


    public function update(Request $request, $id)
    {
        try {

            $validasi = Validator::make($request->all(), [
                'tarif_kode' => 'required',
                'tarif_kelompok' => 'required',
                'tarif_jalan' => 'required|numeric',
                'tarif_promo' => 'required',
                'persen_promo' => 'required',
                'harga_akhir' => 'required',
                'promo_start' => 'required',
                'promo_end' => 'required',
                'status' => 'required'
            ], [
                'tarif_kelompok.required' => 'Kolom Kategori harus diisi.',
                'tarif_kode.required' => 'Kolom Nama Layanan harus diisi.',
                'tarif_jalan.required' => 'Kolom Tarif Layanan harus diisi.',
                'tarif_promo.required' => 'Kolom Jumlah Promo harus diisi.',
                'persen_promo.required' => 'Kolom % Promo harus diisi.',
                'harga_akhir.required' => 'Kolom harga akhir harus diisi.',
                'promo_start.required' => 'Kolom Tanggal Mulai harus diisi.',
                'promo_end.required' => 'Kolom Tanggal Selesai harus diisi.',
                'status.required' => 'Kolom Status Promo harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = TarifLab::updateOrCreate(
                [
                    'tarif_kode' => $request->tarif_kode,
                    'tarif_kelompok' => $request->tarif_kelompok,
                ],
                [

                    'tarif_jalan' => str_replace('.', '', $request->tarif_jalan),
                    'promo_value' => str_replace('.', '', $request->tarif_promo),
                    'promo_percent' => $request->persen_promo,
                    'fix_value' => str_replace('.', '', $request->harga_akhir),
                    'periode_start' => $request->promo_start,
                    'periode_end' => $request->promo_end,
                    'status_promo' => $request->status,
                ]
            );

            if ($data) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('layananpromo.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating layananpromo: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
