<?php

namespace App\Http\Controllers\TabelData;

use App\Http\Controllers\Controller;
use App\Models\LabPeriksa;
use App\Models\LabReference;
use Illuminate\Http\Request;

class TabelLabReferenceController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read tabel/labreference');
    }

    public function index(Request $request)
    {

        $data = LabPeriksa::all();

        return view('tabel.labreference.index', compact('data'));
    }

    public function getReference(Request $request)
    {
        $lab_kode = $request->lab_kode;

        $data = LabReference::query()
            ->where('lab_kode', '=', $lab_kode)
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json($data);
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
