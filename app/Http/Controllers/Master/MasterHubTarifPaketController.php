<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketLab;
use App\Models\TarifLab;
use Illuminate\Http\Request;

class MasterHubTarifPaketController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/hubtarifpaket');
    }

    public function index(Request $request)
    {

        $paket_kode = $request->paket_kode;

        $data = TarifLab::query()
            ->select('paket_labs.paket_kode', 'tarif_labs.id', 'tarif_labs.tarif_kode', 'tarif_labs.tarif_nama')
            ->join('paket_hubungs', 'paket_hubungs.tarif_kode', 'tarif_labs.tarif_kode')
            ->join('paket_labs', 'paket_labs.paket_kode', 'paket_hubungs.paket_kode');

        if (!empty($paket_kode)) {
            $data = $data->where('paket_labs.paket_kode', 'like', '%' . $paket_kode . '%');
        }

        $data = $data->get();

        $paket =  PaketLab::all();

        return view('master.hubtarifpaket.index', compact('data', 'paket'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
