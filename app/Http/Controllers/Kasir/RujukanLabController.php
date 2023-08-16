<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Kota;
use App\Models\PaketLab;
use App\Models\Pasien;
use App\Models\RawatJalan;
use App\Models\Rujukan;
use App\Models\RujukanDetail;
use App\Models\TarifLab;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\UserMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RujukanLabController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:read kasir/rujukan');
    }

    public function index(Request $request)
    {
        $data = Rujukan::all();

        $rujukanId = $data->pluck('rujukan_id');

        $detail = RujukanDetail::query()
            ->join('rujukans', 'rujukans.rujukan_id', 'rujukan_details.rujukan_id')
            ->whereIn('rujukan_details.rujukan_id', $rujukanId)
            ->get();

        $data->each(function ($item) use ($detail) {
            $item->detail = $detail->where('rujukan_id', $item->rujukan_id);
        });

        return view('kasir.rujukan.index', compact('data'));
    }


    public function createNewRujNumber()
    {
        $now = new \DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = Rujukan::orderBy('rujukan_id', 'desc')->first();

        if ($lastTransaksi == null) {
            $newNumber = '0001';
        } else {
            $lastLabNumber = $lastTransaksi->rujukan_no;
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
        return 'RJ-' . $year . $month . $day . '-' . $newNumber;
    }

    public function create()
    {

        $user_mobile = UserMobile::all();

        $autoNumbers = $this->createNewLabNumber();


        return view('kasir.rujukan.create', compact('autoNumbers', 'user_mobile'));
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

                RawatJalan::where('jalan_no_reg', $request->labnoreg)->delete();
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


    public function edit($id)
    {
        $data = Rujukan::query()
            ->join('pasiens', 'pasiens.pasien_nomor_rm', 'rujukans.pasien_nomor_rm')
            ->where('rujukan_id', $id)
            ->first();

        return view('kasir.rujukan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'kodeTarif' => 'required',
            ],
                [
                    'kodeTarif.required' => 'Data layanan kosong.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $data = Rujukan::where('rujukan_id', $id)->first();

            $labDetails = [];

            foreach ($request->kodeTarif as $list => $labDetail) {

                $detail = [
                    'rujukan_id' => $data->rujukan_id,
                    'item_id' => $labDetail,
                    'lab_nama' => $request->namaTarif[$list],
                    'lab_banyak' => 1,
                    'lab_tarif' => $request->tarifPeriksa[$list],
                    'status' => "A",
                ];

                $labDetails[] = $detail;

            }

            RujukanDetail::insert($labDetails);

            $data->total_harga = $request->totalLab;

            if ($data->save()) {
                DB::commit();

                Session::flash('toast_success', 'Data berhasil diubah');
                return redirect()->route('rujukan.index');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating rujukan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal emngubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $data = Rujukan::query()->where('rujukan_id', $id)->first();

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $details = RujukanDetail::where('rujukan_id', $id)->get();

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

    }
}
