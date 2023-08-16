<?php

namespace App\Http\Controllers\TabelData;

use App\Http\Controllers\Controller;
use App\Models\PaketLab;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Illuminate\Http\Request;

class TabelHubTarifPaketController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read tabel/hubpakettarif');
    }

    public function index()
    {

        $paket = PaketLab::query()->where('paket_status', 'A')->orderBy('paket_kode', 'desc')->get();
        $lab = TarifLab::query()->where('tarif_status', 'A')->orderBy('id', 'desc')->get();
        $tarif = TarifVar::query()->where('var_seri', '=', 'LAB')->orderby('var_nama')->get();


        return view('tabel.hubpakettarif.index', compact('paket', 'tarif', 'lab'));
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
    public function show(string $id)
    {
        $data = TarifLab::query()
            ->join('paket_hubungs', 'paket_hubungs.tarif_kode', 'tarif_labs.tarif_kode')
            ->join('paket_labs', 'paket_labs.paket_kode', 'paket_hubungs.paket_kode')
            ->where('tarif_labs.tarif_kode', $id)
            ->first();

        return response()->json($data);
    }

    public function edit($id)
    {
        $data = TarifLab::query()
            ->join('paket_hubungs', 'paket_hubungs.tarif_kode', 'tarif_labs.tarif_kode')
            ->join('paket_labs', 'paket_labs.paket_kode', 'paket_hubungs.paket_kode')
            ->where('tarif_labs.tarif_kode', $id)
            ->first();

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

        $paket_kode = $request->paket_kode;

        $data = TarifLab::query()
            ->select('paket_labs.paket_kode', 'tarif_labs.id', 'tarif_labs.tarif_kode', 'tarif_labs.tarif_nama')
            ->join('paket_hubungs', 'paket_hubungs.tarif_kode', 'tarif_labs.tarif_kode')
            ->join('paket_labs', 'paket_labs.paket_kode', 'paket_hubungs.paket_kode')
            ->orderBy('tarif_labs.tarif_kode', 'desc');


        if (!empty($paket_kode)) {
            $data = $data->where('paket_labs.paket_kode', 'like', '%' . $paket_kode . '%');
        }

        $data = $data->get();

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return '<button type="button" class="btn btn-icon btn-outline-info" onclick="editForm(`' . route('hubtarifpaket.view', $data->tarif_kode) . '`)" ><span class="tf-icons bx bx-info-circle" > </span> </a></td>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
