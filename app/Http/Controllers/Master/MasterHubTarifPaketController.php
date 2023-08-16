<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketHubung;
use App\Models\PaketLab;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MasterHubTarifPaketController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read master/hubtarifpaket');
    }

    public function index()
    {

        $paket = PaketLab::query()->where('paket_status', 'A')->orderBy('paket_kode', 'desc')->get();
        $lab = TarifLab::query()->where('tarif_status', 'A')->orderBy('id', 'desc')->get();
        $tarif = TarifVar::query()->where('var_seri', '=', 'LAB')->orderby('var_nama')->get();


        return view('master.hubtarifpaket.index', compact('paket', 'tarif', 'lab'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'tarif_kode' => 'required',
                'paket_kode' => 'required',
            ], [
                'tarif_kode.required' => 'Kolom tarif kode harus diisi.',
                'paket_kode.required' => 'Kolom paket kode harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $already = PaketHubung::where('tarif_kode', $request->tarif_kode)->where('paket_kode', $request->paket_kode)->first();

            if ($already) {
                Session::flash('toast_failed', 'Data sudah ada');
                return redirect()->back();
            }

            $data = new PaketHubung;
            $data->tarif_kode = $request->tarif_kode;
            $data->paket_kode = $request->paket_kode;
            $data->id_client = 'H002';

            if ($data->save()) {

                Session::flash('toast_success', 'Hubungan tarif dan paket berhasil ditambahkan');
                return redirect()->route('hubtarifpaket.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating hubungan paket dan tarif: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

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

    public function update(Request $request, $id)
    {
        //
    }


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
