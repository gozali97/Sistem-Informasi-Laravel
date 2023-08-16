<?php

namespace App\Http\Controllers\TabelData;

use App\Http\Controllers\Controller;
use App\Models\GroupPaket;
use App\Models\PaketLab;
use Illuminate\Http\Request;

class TabelPaketLabController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read tabel/paketlab');
    }

    public function index(Request $request)
    {
        $data = PaketLab::query()
            ->select('*')
            ->orderBy('paket_kode', 'DESC')
            ->get();
        $groups = GroupPaket::query()
            ->select('*')
            ->orderBy('paket_kelompok', 'ASC')
            ->get();
        return view('tabel.paketlab.index', compact('data', 'groups'));
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
