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

    public function index()
    {
        $kode = TarifVar::query()
            ->where('var_seri', '=', 'LAB')
            ->orderby('var_nama')
            ->get();

        return view('master.hubtarifpemeriksaan.index', compact('kode'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $data = TarifLab::where('tarif_kode', $id)->first();

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = TarifLab::where('tarif_kode', $id)->first();

        return response()->json($data);
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

    public function getData(Request $request)
    {
        $kode = $request->kode;

        $data = TarifLab::query()
            ->select('tarif_labs.*')
            ->orderBy('tarif_kode', 'desc');

        if (!empty($kode)) {
            $data = $data->where('tarif_kelompok', 'like', '%' . $kode . '%');
        }

        $data = $data->get();

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return '<button type="button" class="btn btn-icon btn-outline-info" onclick="editForm(`' . route('hubtarifpemeriksaan.view', $data->tarif_kode) . '`)" ><span class="tf-icons bx bx-info-circle" > </span> </a></td>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
