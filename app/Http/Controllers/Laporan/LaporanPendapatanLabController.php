<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\KasirJalan;
use App\Models\Perusahaan;
use App\Models\RawatJalan;
use Illuminate\Http\Request;

class LaporanPendapatanLabController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read laporan/rekappendapatanlab');
    }

    public function index()
    {
        $penjamin = Perusahaan::query()
            ->where('prsh_status', 'A')
            ->orderBy('prsh_kode', 'asc')
            ->get();

        return view('laporan.rekappendapatanlab.index', compact('penjamin'));
    }

    public function getData(Request $request)
    {
        $data = KasirJalan::query()
            ->join('pasiens', 'pasiens.pasien_nomor_rm', 'kasir_jalans.pasien_nomor_rm')
            ->join('perusahaans', 'perusahaans.prsh_kode', 'kasir_jalans.prsh_kode')
            ->join('transaksis', 'transaksis.lab_no_reg', 'kasir_jalans.kasir_no_reg')
            ->orderBy('kasir_no_reg', "ASC");

        if (!empty($request->NomorRM)) {
            $data = $data->where('pasiens.pasien_nomor_rm', 'ilike', '%' . $request->NomorRM . '%');
        }

        if (!empty($request->nama)) {
            $data = $data->where('pasiens.pasien_nama', 'ilike', '%' . $request->nama . '%');
        }

        if (!empty($request->bulan)) {
            $data = $data->whereMonth('kasir_jalans.kasir_tanggal', $request->bulan);
        }

        if (!empty($request->tahun)) {
            $data = $data->whereYear('kasir_jalans.kasir_tanggal', $request->tahun);
        }

//        if (!empty($request->start) && !empty($request->end)) {
//            $data = $data->whereBetween('rawat_jalans.jalan_tanggal', [$request->start, $request->end]);
//        }

        if (!empty($request->layanan)) {
            $data = $data->where('transaksis.jenis_layanan', $request->layanan);
        }

        if (!empty($request->penjamin)) {
            $data = $data->where('kasir_jalans.prsh_kode', $request->penjamin);
        }

        $data = $data->get();

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('pasien_gender', function ($data) {
                $gender = 'Laki-Laki';
                if ($data->pasien_gender == 'P') {
                    $gender = 'Perempuan';
                }
                return $gender;
            })
            ->addColumn('pasien_tgl_lahir', function ($data) {
                $tglLahir = new \DateTime($data->pasien_tgl_lahir);
                $tglSekarang = new \DateTime();
                $umurInterval = $tglLahir->diff($tglSekarang);
                $umur = $umurInterval->y;

                return $umur;
            })
            ->addColumn('kasir_tanggal', function ($data) {
                return date('d-m-Y', strtotime($data->kasir_tanggal));
            })
            ->addColumn('layanan', function ($data) {
                $layanan = 'Home Service';
                if ($data->jenis_layanan == '0') {
                    $layanan = 'Datang ke Hi-LAB';
                }
                return $layanan;
            })
            ->addColumn('kasir_jumlah', function ($data) {
                return 'Rp. ' . number_format($data->kasir_jumlah, 0, ',', '.');
            })
            ->rawColumns(['pasien_gender', 'pasien_tgl_lahir', 'jalan_tanggal', 'layanan' . 'kasir_jumlah'])
            ->make(true);
    }

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
