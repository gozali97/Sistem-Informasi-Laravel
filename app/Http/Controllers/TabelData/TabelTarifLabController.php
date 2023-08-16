<?php

namespace App\Http\Controllers\TabelData;

use App\Http\Controllers\Controller;
use App\Models\PaketLab;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Illuminate\Http\Request;

class TabelTarifLabController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read tabel/tariflab');
    }

    public function index(Request $request)
    {

        $tarif = $request->tarif;
        $paket = $request->paket;

        $data = TarifLab::select('tarif_labs.*')
            ->join('paket_hubungs', 'paket_hubungs.tarif_kode', 'tarif_labs.tarif_kode')
            ->join('paket_labs', 'paket_labs.paket_kode', 'paket_hubungs.paket_kode')
            ->orderby('id', 'ASC')
            ->distinct();

        if (!empty($tarif)) {
            $data = $data->where('tarif_labs.tarif_kelompok', 'like', '%' . $tarif . '%');
        }

        if (!empty($paket)) {
            $data = $data->where('paket_labs.paket_kode', 'like', '%' . $paket . '%');
        }

        $data = $data->get();

        $tarif = TarifVar::query()->where('var_seri', '=', 'LAB')->orderby('var_nama')->get();

        $paket = PaketLab::all();

        return view('tabel.tabeltarif.index', compact('data', 'tarif', 'paket'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
