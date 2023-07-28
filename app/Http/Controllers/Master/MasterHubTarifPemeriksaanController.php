<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketHubung;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Illuminate\Http\Request;

class MasterHubTarifPemeriksaanController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/hubtarifpemeriksaan');
    }

    public function index(Request $request)
    {

        $kode = $request->kode;

        $data = TarifLab::query()
            ->select('tarif_labs.*');

        if (!empty($kode)) {
            $data = $data->where('tarif_kelompok', 'like', '%' . $kode . '%');
        }

        $data = $data->get();

        $kode =  TarifVar::query()
            ->where('var_seri', '=', 'LAB')
            ->orderby('var_nama')
            ->get();

        return view('master.hubtarifpemeriksaan.index', compact('data', 'kode'));
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
