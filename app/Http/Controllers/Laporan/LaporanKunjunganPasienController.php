<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Perusahaan;
use App\Models\RawatJalan;
use Illuminate\Http\Request;

class LaporanKunjunganPasienController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:read laporan/rekapkunjunganlab');
    }

    public function index()
    {

        $pengirim = Group::where('grup_jenis', '=', 'PRSH')->orderBy('grup_kode', 'asc')->distinct()->get()->unique('grup_kode');

        $penjamin = Perusahaan::query()
            ->where('prsh_status', 'A')
            ->orderBy('prsh_kode', 'asc')
            ->get();

        return view('laporan.kunjunganpasien.index', compact('pengirim', 'penjamin'));
    }

    public function getData(Request $request)
    {
        $data = RawatJalan::query()
            ->join('pasiens', 'pasiens.pasien_nomor_rm', 'rawat_jalans.pasien_nomor_rm')
            ->join('groups', 'groups.grup_kode', 'rawat_jalans.pngrm_kode')
            ->join('perusahaans', 'perusahaans.prsh_kode', 'rawat_jalans.prsh_kode')
            ->join('transaksis', 'transaksis.lab_no_reg', 'rawat_jalans.jalan_no_reg')
            ->where('groups.grup_jenis', '=', 'PRSH')
            ->orderBy('jalan_no_reg', "ASC");

        if (!empty($request->NomorRM)) {
            $data = $data->where('pasiens.pasien_nomor_rm', 'ilike', '%' . $request->NomorRM . '%');
        }

        if (!empty($request->nama)) {
            $data = $data->where('pasiens.pasien_nama', 'ilike', '%' . $request->nama . '%');
        }

        if (!empty($request->bulan)) {
            $data = $data->whereMonth('rawat_jalans.jalan_tanggal', $request->bulan);
        }

        if (!empty($request->tahun)) {
            $data = $data->whereYear('rawat_jalans.jalan_tanggal', $request->tahun);
        }

//        if (!empty($request->start) && !empty($request->end)) {
//            $data = $data->whereBetween('rawat_jalans.jalan_tanggal', [$request->start, $request->end]);
//        }

        if (!empty($request->layanan)) {
            $data = $data->where('transaksis.jenis_layanan', $request->layanan);
        }

        if (!empty($request->pengirim)) {
            $data = $data->where('rawat_jalans.pngrm_kode', $request->pengirim);
        }
        if (!empty($request->penjamin)) {
            $data = $data->where('rawat_jalans.prsh_kode', $request->penjamin);
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
            ->addColumn('jalan_tanggal', function ($data) {
                return date('d-m-Y', strtotime($data->jalan_tanggal));
            })
            ->addColumn('layanan', function ($data) {
                $layanan = 'Home Service';
                if ($data->jenis_layanan == '0') {
                    $layanan = 'Datang ke Hi-LAB';
                }
                return $layanan;
            })
            ->rawColumns(['pasien_gender', 'pasien_tgl_lahir', 'jalan_tanggal', 'layanan'])
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
