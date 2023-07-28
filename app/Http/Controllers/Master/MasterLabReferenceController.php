<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\LabPeriksa;
use App\Models\LabReference;
use Illuminate\Http\Request;

class MasterLabReferenceController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/labreference');
    }

    public function index(Request $request)
    {

        $data = LabPeriksa::all();

        return view('master.labreference.index', compact('data'));
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

    public function updateReference(Request $request)
    {
        $data = LabReference::find($request->id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->ref_value = $request->nilai;

        if ($data->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data!'
            ]);
        }
    }

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
