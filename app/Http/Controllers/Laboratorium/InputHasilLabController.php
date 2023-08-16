<?php

namespace App\Http\Controllers\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Group;
use App\Models\Kota;
use App\Models\LabHasil;
use App\Models\LabReference;
use App\Models\Pasien;
use App\Models\Perusahaan;
use App\Models\RawatJalan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InputHasilLabController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:read laboratorium/inputhasillab');
    }

    public function index()
    {
        $data = Transaksi::query()
            ->join('perusahaans', 'perusahaans.prsh_kode', 'transaksis.prsh_kode')
            ->orderBy('transaksi_id', "DESC")
            ->where('transaksis.lab_byr_jenis', 1)
            ->get();

        $labNomor = $data->pluck('lab_nomor');

        $detail = TransaksiDetail::query()
            ->join('transaksis', 'transaksis.lab_nomor', 'transaksi_details.lab_nomor')
            ->whereIn('transaksi_details.lab_nomor', $labNomor)
            ->get();

        $data->each(function ($item) use ($detail) {
            $item->detail = $detail->where('lab_nomor', $item->lab_nomor);
        });

        $hasil = LabHasil::where('lab_nomor', $labNomor)->get();

        $data->each(function ($item) use ($hasil) {
            $item->hasil = $hasil->where('lab_nomor', $item->lab_nomor);
        });
        return view('laboratorium.hasillab.index', compact('data'));
    }

    public function getData(Request $request)
    {
        $data = Transaksi::query()
            ->join('perusahaans', 'perusahaans.prsh_kode', 'transaksis.prsh_kode')
            ->orderBy('transaksi_id', "DESC")
            ->where('transaksis.lab_byr_jenis', 1);

        if (!empty($request->NomorRM)) {
            $data = $data->where('transaksis.pasien_nomor_rm', 'ilike', '%' . $request->NomorRM . '%');
        }

        if (!empty($request->nama)) {
            $data = $data->where('transaksis.pasien_nama', 'ilike', '%' . $request->nama . '%');
        }

        if (!empty($request->start) && !empty($request->end)) {
            $data = $data->whereBetween('transaksis.lab_tanggal', [$request->start, $request->end]);
        }

        $data = $data->get();

        $labNomor = $data->pluck('lab_nomor');

        $detail = TransaksiDetail::query()
            ->join('transaksis', 'transaksis.lab_nomor', 'transaksi_details.lab_nomor')
            ->whereIn('transaksi_details.lab_nomor', $labNomor)
            ->get();

        $data->each(function ($item) use ($detail) {
            $item->detail = $detail->where('lab_nomor', $item->lab_nomor);
        });

        return response()->json($data);
    }

    public function create($id)
    {
        $data = Transaksi::query()
            ->join('perusahaans', 'perusahaans.prsh_kode', 'transaksis.prsh_kode')
            ->join('transaksi_details', 'transaksi_details.lab_nomor', 'transaksis.lab_nomor')
            ->join('tarif_labs', 'tarif_labs.tarif_kode', 'transaksi_details.lab_kode_detail')
            ->join('lab_hubungs', 'lab_hubungs.tarif_lab_kode', 'transaksi_details.lab_kode_detail')
            ->join('lab_periksas', 'lab_periksas.lab_kode', 'lab_hubungs.lab_kode')
            ->where('transaksis.transaksi_id', $id)
            ->orderBy('lab_periksas.lab_kode', 'asc')
            ->get();

        return view('laboratorium.hasillab.create', compact('data'));
    }

    public function createNewLabNumber()
    {
        $now = new \DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = LabHasil::orderBy('lab_auto_nomor', 'desc')->first();

        if ($lastTransaksi == null) {
            $newNumber = '0001';
        } else {
            $lastLabNumber = $lastTransaksi->lab_no_reg;
            $lastYear = substr($lastLabNumber, 3, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $newNumber = '0001';
            } else {
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        }

        return 'LH-' . $year . $month . $day . '-' . $newNumber;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'catatan' => 'required',
            ],
                [
                    'catatan.required' => 'Kolom catatan harus diisi.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $count = LabHasil::count();


            $labDetails = [];

            foreach ($request->labnomor as $list => $labNomor) {

                $hasil = [
                    'lab_nomor' => $labNomor,
                    'pasien_nomor_rm' => $request->pasiennomor[$list],
                    'lab_auto_nomor' => $this->createNewLabNumber(),
                    'user_mobile_id' => $request->mobile[$list],
                    'lab_kode' => $request->labkode[$list],
                    'lab_nama' => $request->labnama[$list],
                    'lab_metode' => '0',
                    'lab_hasil' => $request->labhasil[$list],
                    'lab_satuan' => $request->labsatuan[$list],
                    'lab_harga_norm' => $request->labtarif,
                    'lab_keterangan' => $request->catatan,
                    'lab_numeric' => 1,
                    'status_pemeriksaan' => 'A',
                    'id_client' => 'H002',
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $labHasils[] = $hasil;


            }
            LabHasil::insert($labHasils);
            DB::commit();

            Session::flash('toast_success', 'Data berhasil ditambah');
            return redirect()->route('inputhasillab.index');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            Log::error('Error creating daftarlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function printHasilPeriksa($id)
    {
        $data = Transaksi::where('lab_nomor', '=', $id)->first();

        $tlab = Transaksi::leftjoin('pasiens as pas', 'pas.pasien_nomor_rm', '=', 'transaksis.pasien_nomor_rm')
            ->where('lab_nomor', '=', $data->lab_nomor)->get();
        foreach ($tlab as $lab) {
            $gender = $lab->pasien_gender;
        }
        $tlabhasil = LabHasil::leftjoin('lab_periksas as per', 'per.lab_kode', '=', 'lab_hasils.lab_kode')
            ->leftjoin('lab_references as ref', function ($join) {
                $join->on('ref.lab_kode', '=', 'per.lab_kode')
                    ->where('ref.ref_range', '=', 'Normal');
            })
            ->join('lab_hubungs', 'lab_hubungs.lab_kode', 'lab_hasils.lab_kode')
            ->join('transaksi_details AS dt', 'dt.lab_kode_detail', 'lab_hubungs.tarif_lab_kode')
            ->select('dt.lab_nama AS nama', 'lab_hasils.lab_nama', 'ref.ref_value', 'lab_hasils.lab_hasil', 'lab_hasils.lab_satuan',)
            ->where('lab_hasils.lab_nomor', $id)
            ->where('dt.lab_nomor', $id)
            ->orderBy('nama')
            ->distinct()
            ->get();

        $pdf = Pdf::loadView('laboratorium.hasillab.printhasil', compact('tlab', 'tlabhasil'));

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-hasil-pemeriksaan.pdf"'
        ]);

    }

    public function sendHasilPeriksa($id)
    {
        $data = Transaksi::where('lab_nomor', '=', $id)->first();

        $pasien = Pasien::where('pasien_nomor_rm', $data->pasien_nomor_rm)->first();

        $tlab = Transaksi::leftjoin('pasiens as pas', 'pas.pasien_nomor_rm', '=', 'transaksis.pasien_nomor_rm')
            ->where('lab_nomor', '=', $data->lab_nomor)->get();
        foreach ($tlab as $lab) {
            $gender = $lab->pasien_gender;
        }
        $tlabhasil = LabHasil::leftjoin('lab_periksas as per', 'per.lab_kode', '=', 'lab_hasils.lab_kode')
            ->leftjoin('lab_references as ref', function ($join) {
                $join->on('ref.lab_kode', '=', 'per.lab_kode')
                    ->where('ref.ref_range', '=', 'Normal');
            })
            ->join('lab_hubungs', 'lab_hubungs.lab_kode', 'lab_hasils.lab_kode')
            ->join('transaksi_details AS dt', 'dt.lab_kode_detail', 'lab_hubungs.tarif_lab_kode')
            ->select('dt.lab_nama AS nama', 'lab_hasils.lab_nama', 'ref.ref_value', 'lab_hasils.lab_hasil', 'lab_hasils.lab_satuan',)
            ->where('lab_hasils.lab_nomor', $id)
            ->where('dt.lab_nomor', $id)
            ->orderBy('nama')
            ->distinct()
            ->get();

        $data["email"] = $pasien->pasien_email;
        $data["title"] = "From Hi-Lab";
        $data["body"] = "This is Demo";

        $datas['tlab'] = $tlab;
        $datas['tlabhasil'] = $tlabhasil;

        $pdf = PDF::loadView('laboratorium.hasillab.printhasil', $datas);


        Mail::send('laboratorium.hasillab.sendhasil', ['data' => $data, 'datas' => $datas], function ($message) use ($data, $pdf) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), "hasil.pdf");
        });

        Session::flash('toast_success', 'Hasil Pemeriksaan berhasil dikirim email');
        return redirect()->route('inputhasillab.index');
    }

    public function getRawatJalan()
    {
        $data = RawatJalan::query()
            ->join('pasiens', 'pasiens.pasien_nomor_rm', 'rawat_jalans.pasien_nomor_rm')
            ->join('admvars as gender', function ($join) {
                $join->on('gender.var_kode', '=', 'pasiens.pasien_gender')
                    ->where('gender.var_seri', '=', 'GENDER');
            })
            ->join('admvars as jenis', function ($join) {
                $join->on('jenis.var_kode', '=', 'pasiens.pasien_jenis')
                    ->where('jenis.var_seri', '=', 'JENISPAS');
            })
            ->join('perusahaans', 'perusahaans.prsh_kode', 'rawat_jalans.prsh_kode')
            ->join('user_mobiles', 'user_mobiles.id', 'pasiens.user_mobile_id')
            ->select('rawat_jalans.*', 'pasiens.pasien_tgl_lahir', 'gender.var_nama as gender', 'jenis.var_nama as jenis', 'perusahaans.prsh_nama')
            ->where('rawat_jalans.jalan_status', 'P')
            ->orderBy('jalan_no_reg', 'ASC')
            ->get();

        return response()->json($data);
    }
}
