<?php

namespace App\Http\Controllers\Kasir;

use App\Helpers\autoNumberKasir;
use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Kota;
use App\Models\PaketLab;
use App\Models\Pasien;
use App\Models\RawatJalan;
use App\Models\TarifLab;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\UserMobile;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransaksiLabController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:read kasir/transaksi');
    }

    public function index(Request $request)
    {
        $data = Transaksi::all();

        $labNomor = $data->pluck('lab_nomor');

        $detail = TransaksiDetail::query()
            ->join('transaksis', 'transaksis.lab_nomor', 'transaksi_details.lab_nomor')
            ->whereIn('transaksi_details.lab_nomor', $labNomor)
            ->get();

        $data->each(function ($item) use ($detail) {
            $item->detail = $detail->where('lab_nomor', $item->lab_nomor);
        });

        return view('kasir.transaksi.index', compact('data'));
    }

    public function createNewLabNumber()
    {
        $now = new DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = Transaksi::orderBy('lab_nomor', 'desc')->first();

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

        return 'PK1-' . $year . $month . $day . '-' . $newNumber;
    }

    public function create()
    {

        $user_mobile = UserMobile::all();

        $autoNumbers = $this->createNewLabNumber();

        return view('kasir.transaksi.create', compact('autoNumbers', 'user_mobile'));
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'jamperiksa' => 'required',
                'jenis_layanan' => 'required',
                'kodeTarif' => 'required',
                'pasien_rm' => 'required',
            ],
                [
                    'jamperiksa.required' => 'Kolom Jam Periksa harus diisi.',
                    'pasien_rm.required' => 'Data pasien kosong.',
                    'kodeTarif.required' => 'Data layanan kosong.',
                    'jenis_layanan.required' => 'Kolom Jenis Layanan Test harus diisi.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $labno = $request->notrans;


            $pasien = Pasien::where('pasien_nomor_rm', $request->pasiennorm)->first();
            $dokter = Dokter::where('dokter_kode', $request->dokterkode)->first();


            $kota = Kota::where('city_id', $pasien->pasien_kota)->first();

            $tgl = date('y') . date('m');

            $month = date('m');
            $years = date('Y');

            $data = new Transaksi;

            $data->lab_nomor = $request->notrans;
            $data->user_mobile_id = $pasien->user_mobile_id;
            $data->lab_tanggal = date('Y-m-d H:i:s');
            $data->lab_reff_no = chr((int)$month + 64) . '.' . Str::substr($years, 2) . '.';
            $data->lab_jenis = 'J';
            $data->lab_no_reg = $request->labnoreg;
            $data->lab_pas_baru = $request->jenispasienbarulama;
            $data->tt_nomor = '';
            $data->kelas_kode = 'J';
            $data->dokter_kode = $request->dokterkode;
            $data->dokter_nama = $dokter->dokter_nama;
            $data->lab_jam_sample = $request->jamperiksa;
            $data->pasien_nomor_rm = $request->pasiennorm;
            $data->pasien_gender = $pasien->pasien_gender;
            $data->pasien_nama = $request->nama;
            $data->pasien_alamat = $request->alamat;
            $data->pasien_kota = $kota->city_name;
            $data->pasien_umur_thn = $request->pasienumurthn;
            $data->pasien_umur_bln = $request->pasienumurbln;
            $data->pasien_umur_hr = $request->pasienumurhari;
            $data->lab_petugas = '';
            $data->lab_cat_hasil = '';
            $data->prsh_kode = $request->prshkode;
            $data->lab_jumlah = $request->totalLab;
            if ($request->prshkode == '0-0000') {
                $data->lab_pribadi = $request->totalHarga;
            } else {
                $data->lab_asuransi = $request->totalLab;
            }
            $data->lab_pribadi = $request->totalLab;
            $data->lab_byr_jenis = '1';
            $data->user_id = Auth::User()->username;
            $data->user_id2 = Auth::User()->username;
            $data->user_date = date('Y-m-d H:i:s');
            $data->user_date2 = date('Y-m-d H:i:s');
            $data->lab_ambil_status = '0';
            $data->lab_ambil_jam = $request->jamperiksa;
            $data->lab_cetak_status = '0';
            $data->jenis_layanan = $request->jenis_layanan;
            $data->id_client = Auth::User()->idlab;

            if ($data->save()) {

                $latestLabAutoNomor = TransaksiDetail::orderBy('lab_auto_nomor', 'desc')->first();

                if ($latestLabAutoNomor) {
                    $labAutoNomor = (int)$latestLabAutoNomor->lab_auto_nomor + 1;
                } else {
                    $labAutoNomor = 1;
                }

                $labDetails = [];

                foreach ($request->kodeTarif as $list => $labDetail) {

                    $labAutoNomor = $labAutoNomor + $list;

                    $detail = [
                        'lab_nomor' => $labno,
                        'lab_kode_detail' => $labDetail,
                        'lab_nama' => $request->namaTarif[$list],
                        'lab_banyak' => 1,
                        'lab_auto_nomor' => $labAutoNomor,
                        'lab_tarif' => $request->tarifPeriksa[$list],
                        'lab_tarif_askes' => 0,
                        'lab_diskon_prs' => $request->discPercentage[$list],
                        'lab_diskon' => $request->discRp[$list],
                        'lab_asuransi' => $request->asuransi[$list],
                        'lab_pribadi' => $request->bayarSendiri[$list],
                        'id_client' => Auth::User()->idlab,
                        'lab_jumlah' => $request->totalLab,
                    ];

                    $labDetails[] = $detail;

                }

                TransaksiDetail::insert($labDetails);
                DB::commit();

                Session::flash('toast_success', 'Data berhasil ditambah');
                return redirect()->route('transaksi.index');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating daftarlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $data = Transaksi::query()->where('transaksi_id', $id)->first();

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $details = TransaksiDetail::where('lab_nomor', $data->lab_nomor)->get();

        foreach ($details as $detail) {
            $detail->delete();
        }

        if ($data->delete()) {
            Session::flash('toast_success', 'Data berhasil dihapus');
        } else {
            Session::flash('toast_failed', 'Data gagal dihapus');
        }
        return redirect()->back();
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
            ->where('rawat_jalans.jalan_status', 'D')
            ->orderBy('jalan_no_reg', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function getPemeriksaan()
    {
        $data = TarifLab::query()
            ->orderBy('tarif_kode', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function getPaket()
    {

        $data = PaketLab::query()
            ->orderBy('paket_kode', 'ASC')
            ->get();

        return response()->json($data);
    }
}
