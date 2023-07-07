<?php

namespace App\Http\Controllers\Api;

use App\Models\TLab;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\KasirJalan;
use App\Models\PaketLab;
use Illuminate\Support\Facades\Validator;
use App\Models\Pasien;
use App\Models\RawatJalan;
use App\Models\TarifLab;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{

    public function index()
    {
        $pasien = Pasien::all();
        return $this->success($pasien);
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
            // dd("1");
        } else {
            $lastLabNumber = $lastTransaksi->lab_nomor;

            // $lastLabNumber
            $lastYear = substr($lastLabNumber, 4, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            $lastDayTrans = explode("-", $lastLabNumber);
            $lastDayTrans = $lastDayTrans[1];
            $today = $year . $month . $day;
            // dd($lastDayTrans." ". $today);
            // dd($lastLabNumber);

            if ($lastDayTrans != $today) {
                $newNumber = '0001';
            } else {
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        }

        return  'PK1-' . $year . $month . $day . '-' . $newNumber;
    }


    public function createNewRegNumber()
    {
        $now = new DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = RawatJalan::orderBy('jalan_no_reg', 'desc')->first();
        // dd($lastTransaksi);

        if ($lastTransaksi == null) {
            $newNumber = '0001';
        } else {
            $lastLabNumber = $lastTransaksi->jalan_no_reg;
            $lastYear = substr($lastLabNumber, 3, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $lastNumber = 0;
            }
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }
        return 'RP-' . $year . $month . $day . '-' . $newNumber;
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();
        Log::warning(json_encode($data));
        DB::beginTransaction();

        try {
            $transaksis = [];
            $detailTransaksis = [];
            $rawatJalans = [];
            $paket_data = [];
            $layanan_data = [];

            $lab_nomor_arr = array();
            $lab_reg_arr = array();

            for ($i = 0; $i < count($data['pasien']); $i++) {
                $lab_nomor = $this->createNewLabNumber();
                $lab_no_reg = $this->createNewRegNumber();
                // $paketKode = $data['pasien'][$i]['paket_kode'];
                array_push($lab_nomor_arr, $lab_nomor);
                array_push($lab_reg_arr, $lab_no_reg);

                $dokter = Dokter::where('dokter_kode', $data['dokter_kode'])->first();
                $dokter_nama = "";
                if ($dokter) {
                    $dokter_nama = $dokter->dokter_nama_lengkap;
                }

                $pasien = Pasien::query()
                    ->join('kota', 'kota.city_id', 'pasiens.pasien_kota')
                    ->where('pasien_nomor_rm', $data['pasien'][$i]['pasien_nomor_rm'])->first();


                if ($pasien) {
                    $pasien_nama = $pasien->pasien_nama;
                    $pasien_gender = $pasien->pasien_gender;
                    $pasien_alamat = $pasien->pasien_alamat;
                    $pasien_kota = $pasien->city_name;
                    $tanggal_lahir = \Carbon\Carbon::createFromFormat('m/d/Y', $pasien->pasien_tgl_lahir)->startOfDay();
                    $umur = $tanggal_lahir->diff(\Carbon\Carbon::now());
                    $pasien_umur_hari = $umur->days;
                    $pasien_umur_bulan = $umur->y * 12 + $umur->m;
                    $pasien_umur_tahun = $umur->y;
                }

                $rawatJalan = new RawatJalan();
                $rawatJalan->jalan_no_reg = $lab_reg_arr[$i];
                $rawatJalan->pasien_nomor_rm = $data['pasien'][$i]['pasien_nomor_rm'];
                $rawatJalan->user_mobile_id = $data['user_mobile_id'];
                $rawatJalan->pasien_gender = $pasien_gender;
                $rawatJalan->pasien_nama = $pasien_nama;
                $rawatJalan->pasien_alamat = $pasien_alamat;
                $rawatJalan->pasien_kota = $pasien_kota;
                $rawatJalan->pasien_umur_thn = $pasien_umur_tahun;
                $rawatJalan->pasien_umur_bln = $pasien_umur_bulan;
                $rawatJalan->pasien_umur_hr = $pasien_umur_hari;
                $rawatJalan->jalan_tanggal = date('Y-m-d H:m:s');
                $rawatJalan->unit_kode = 32;
                $rawatJalan->dokter_kode = $data['dokter_kode'];
                $rawatJalan->pngrm_kode = $data['pengirim_kode'];
                $rawatJalan->prsh_kode = $data['penjamin_kode'];
                $rawatJalan->jalan_no_urut = substr($lab_reg_arr[$i], -2);
                $rawatJalan->jalan_asal_pasien = '7';
                $rawatJalan->jalan_pas_baru = 'L';
                $rawatJalan->jalan_cara_daftar = 1;
                $rawatJalan->jalan_status = 1;
                $rawatJalan->jalan_jenis_bayar = 'S';
                $rawatJalan->jalan_daftar = (int)str_replace(',', '', 0);
                $rawatJalan->jalan_kartu = 0.00;
                $rawatJalan->jalan_periksa = 0.00;
                $rawatJalan->jalan_jumlah = 0.00;
                $rawatJalan->jalan_potongan = 0.00;
                $rawatJalan->id_client = 'H002';
                $rawatJalan->save();

                array_push($rawatJalans, $rawatJalan);

                $transaksi = new Transaksi;
                $transaksi->lab_nomor = $lab_nomor_arr[$i];
                $transaksi->user_mobile_id = $data['user_mobile_id'];
                $transaksi->lab_tanggal = date('Y-m-d');
                $transaksi->lab_jenis = "J";
                $transaksi->lab_pas_baru = "L";
                $transaksi->kelas_kode = 'J';
                $transaksi->lab_no_reg = $lab_reg_arr[$i];
                $transaksi->dokter_kode = $data['dokter_kode'];
                $transaksi->dokter_nama = $dokter_nama;
                $transaksi->user_date = date('Y-m-d');
                $transaksi->lab_cetak_status = 0;
                $transaksi->pasien_nomor_rm = $data['pasien'][$i]['pasien_nomor_rm'];
                $transaksi->pasien_nama = $pasien_nama;
                $transaksi->pasien_gender = $pasien_gender;
                $transaksi->pasien_alamat = $pasien_alamat;
                $transaksi->pasien_kota = $pasien_kota;
                $transaksi->pasien_umur_thn = $pasien_umur_tahun;
                $transaksi->pasien_umur_bln = $pasien_umur_bulan;
                $transaksi->pasien_umur_hr = $pasien_umur_hari;
                $transaksi->id_client = "H002";
                $transaksi->lab_ambil_jam = $data['jadwal_jam'];
                $transaksi->lab_jumlah = $data['pasien'][$i]['lab_sub_total'];
                $transaksi->save();

                $transaksis[$i]['pasien'] = $transaksi;

                $paketKode = $data['pasien'][$i]['paket'];

                if ($paketKode) {
                    foreach ($data['pasien'][$i]['paket'] as $paket) {

                        $paket_kode = $paket['paket_kode'];

                        $item = TarifLab::query()
                            ->join('paket_hubungs', function ($join) {
                                $join->on('paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode');
                            })
                            ->join('paket_labs', function ($join) use ($paket_kode) {
                                $join->on('paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
                                    ->where('paket_labs.paket_kode', $paket_kode);
                            })
                            ->select('tarif_labs.tarif_kode', 'tarif_labs.tarif_nama as lab_nama', 'tarif_labs.tarif_jalan as lab_tarif', 'tarif_labs.promo_value as lab_diskon', 'tarif_labs.promo_percent as lab_diskon_prs', 'tarif_labs.fix_value as lab_jumlah', 'tarif_labs.tarif_jalan as lab_pribadi')
                            ->groupBy('tarif_labs.tarif_kode', 'tarif_labs.tarif_nama', 'tarif_labs.tarif_jalan', 'tarif_labs.promo_value', 'tarif_labs.promo_percent', 'tarif_labs.fix_value', 'tarif_labs.tarif_jalan')
                            ->get();

                        foreach ($item as $details) {
                            $detailTransaksi = new TransaksiDetail();
                            $detailTransaksi->lab_nomor =  $transaksi->lab_nomor;
                            $detailTransaksi->lab_kode_detail = $details->tarif_kode;
                            $detailTransaksi->lab_nama = $details->lab_nama;
                            $detailTransaksi->lab_tarif = $details->lab_tarif;
                            $detailTransaksi->lab_diskon = $details->lab_diskon;
                            $detailTransaksi->lab_diskon_prs = $details->lab_diskon_prs;
                            $detailTransaksi->lab_jumlah = $details->lab_jumlah;
                            $detailTransaksi->lab_pribadi = $details->lab_pribadi;
                            $detailTransaksi->lab_banyak = 1;
                            $detailTransaksi->id_client = "H002";
                            $detailTransaksi->created_at = date("Y-m-d");
                            $transaksi->details()->save($detailTransaksi);
                            $transaksis[$i]['paket'][$paket_kode][] = $detailTransaksi;
                        }
                    };
                }

                $detail = $data['pasien'][$i]['layanan'];

                if ($detail) {
                    foreach ($data['pasien'][$i]['layanan'] as $detail) {

                        $detailTransaksi = new TransaksiDetail();
                        $detailTransaksi->lab_nomor =  $transaksi->lab_nomor;
                        $detailTransaksi->lab_kode_detail = $detail['tarif_kode'];
                        $detailTransaksi->lab_nama = $detail['lab_nama'];
                        $detailTransaksi->lab_tarif = $detail['lab_tarif'];
                        $detailTransaksi->lab_diskon = $detail['lab_diskon'];
                        $detailTransaksi->lab_diskon_prs = $detail['lab_diskon_prs'];
                        $detailTransaksi->lab_jumlah = $detail['lab_jumlah'];
                        $detailTransaksi->lab_pribadi = $detail['lab_tarif'];
                        $detailTransaksi->lab_banyak = 1;
                        $detailTransaksi->id_client = "H002";
                        $detailTransaksi->created_at = date("Y-m-d");
                        $detailTransaksi->save();
                        $transaksis[$i]['layanan'][] = $detailTransaksi;
                        // dd($transaksis);

                        // array_push($transaksis[0], $detailTransaksi);
                    }
                }


                // array_push($transaksis, $transaksi);
                // array_push($detailTransaksis, $detailTransaksi);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil',
                'transaksis' => $transaksis,
                // 'details' =>[
                //     'paket' => $paket_data,
                //     'layanan' => $layanan_data
                // ]
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function viewTransaksi(Request $request)
    {
        $mobileId = $request->input('user_mobile_id');
        $pasienIds = array_column($request->input('pasien'), 'pasien_nomor_rm');

        $data = [];

        foreach ($pasienIds as $pasienId) {
            $transaksi = Transaksi::query()
                ->join('transaksi_details', 'transaksi_details.lab_nomor', 'transaksis.lab_nomor')
                ->join('pasiens', 'transaksis.pasien_nomor_rm', 'transaksis.pasien_nomor_rm')
                ->join('tarif_labs', 'tarif_labs.tarif_kode', 'transaksi_details.lab_kode_detail')
                ->select('transaksis.lab_nomor', 'transaksis.lab_jumlah', 'transaksis.pasien_nomor_rm', 'pasiens.pasien_nama')
                ->where('transaksis.user_mobile_id', $mobileId)
                ->where('transaksis.pasien_nomor_rm', $pasienId)
                ->whereNull('transaksis.lab_byr_nomor')
                ->orderBy('transaksis.created_at', 'DESC')
                ->first();

            if ($transaksi) {
                $transaksiDetails = TransaksiDetail::query()
                    ->join('tarif_labs', 'tarif_labs.tarif_kode', 'transaksi_details.lab_kode_detail')
                    ->select('transaksi_details.lab_kode_detail', 'tarif_labs.tarif_nama', 'transaksi_details.lab_tarif', 'transaksi_details.lab_diskon', 'transaksi_details.lab_diskon_prs', 'transaksi_details.lab_jumlah', 'transaksi_details.lab_pribadi')
                    ->where('transaksi_details.lab_nomor', $transaksi->lab_nomor)
                    ->get();

                $paket = PaketLab::query()
                    ->join('paket_hubungs', 'paket_hubungs.paket_kode', 'paket_labs.paket_kode')
                    ->join('tarif_labs', 'tarif_labs.tarif_kode', 'paket_hubungs.tarif_kode')
                    ->get();

                $dataPaket = [];
                $dataLayanan = [];

                foreach ($transaksiDetails as $detail) {
                    $paketTarifKode = $paket->where('tarif_kode', $detail->lab_kode_detail)->where('paket_kode')->first();


                    if ($paketTarifKode) {
                        $paketKode = $paketTarifKode->paket_kode;
                        $dataPaket[$paketKode]['tarif'][] = $detail->toArray();
                        $dataPaket[$paketKode]['paket_kode'] = $paketKode;
                    } else {
                        $dataLayanan[] = $detail->toArray();
                    }
                }

                $data[] = [
                    'pasien_nomor_rm' => $transaksi->pasien_nomor_rm,
                    'pasien_nama' => $transaksi->pasien_nama,
                    'lab_nomor' => $transaksi->lab_nomor,
                    'lab_jumlah' => $transaksi->lab_jumlah,
                    'paket' => array_values($dataPaket),
                    'layanan' => $dataLayanan,
                ];
                return response()->json([
                    'status' => 'success',
                    'message' => 'berhasil',
                    'transaksis' => $data,
                ], 201);
            }
        }
    }




    // public function viewTransaksi(Request $request)
    // {
    //     $mobileId = $request->input('user_mobile_id');
    //     $pasienIds = array_column($request->input('pasien'), 'pasien_nomor_rm');

    //     $data = [];

    //     foreach ($pasienIds as $pasienId) {
    //         $transaksi = Transaksi::query()
    //             ->join('transaksi_details', 'transaksi_details.lab_nomor', 'transaksis.lab_nomor')
    //             ->join('pasiens', 'transaksis.pasien_nomor_rm', 'transaksis.pasien_nomor_rm')
    //             ->join('tarif_labs', 'tarif_labs.tarif_kode', 'transaksi_details.lab_kode_detail')
    //             ->select('transaksis.lab_nomor', 'transaksis.lab_jumlah','transaksis.pasien_nomor_rm', 'pasiens.pasien_nama')
    //             ->where('transaksis.user_mobile_id', $mobileId)
    //             ->where('transaksis.pasien_nomor_rm', $pasienId)
    //             ->whereNull('transaksis.lab_byr_nomor')
    //             ->orderBy('transaksis.created_at', 'DESC')
    //             ->first();

    //         if ($transaksi) {
    //             $transaksiDetails = TransaksiDetail::query()
    //                 ->join('tarif_labs', 'tarif_labs.tarif_kode', 'transaksi_details.lab_kode_detail')
    //                 ->select('tarif_labs.tarif_nama', 'transaksi_details.lab_tarif', 'transaksi_details.lab_diskon', 'transaksi_details.lab_diskon_prs', 'transaksi_details.lab_jumlah', 'transaksi_details.lab_pribadi')
    //                 ->where('transaksi_details.lab_nomor', $transaksi->lab_nomor)
    //                 ->get();

    //             $transaksiData = $transaksi->toArray();
    //             $transaksiData['details'] = $transaksiDetails->toArray();
    //             $data[] = $transaksiData;
    //         }
    //     }

    //     if (!empty($data)) {
    //         return $this->success($data);
    //     } else {
    //         return $this->error("Data tidak ditemukan");
    //     }
    // }



    public function show($id)
    {
        $pasien = Pasien::where('pasien_nomor_rm', '=', $id)->first();
        return $this->success($pasien);
    }

    public function pesanKembali(Request $request)
    {
        $nomorRM = $request->input('pasien_nomor_rm');

        if ($nomorRM) {
            $result = Transaksi::query()
                ->join('transaksi_details', 'transaksi_details.lab_nomor', '=', 'transaksis.lab_nomor')
                ->join('tarif_labs', 'tarif_labs.tarif_kode', '=', 'transaksi_details.lab_kode_detail')
                ->join('paket_hubungs', 'paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode')
                // ->join('paket_labs', 'paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
                // ->join('tarif_vars', 'tarif_vars.var_kode', '=', 'tarif_labs.tarif_kelompok')
                // ->select('tarif_vars.var_seri', 'tarif_labs.tarif_kelompok', 'tarif_vars.var_nama', 'tarif_labs.tarif_nama', 'tarif_labs.tarif_jalan', 'tarif_labs.path_gambar', 'paket_labs.paket_diskon', 'tarif_labs.promo_percent', 'tarif_labs.promo_value', 'paket_labs.deskripsi', 'paket_labs.catatan', 'paket_labs.manfaat', 'tarif_labs.periode_start', 'tarif_labs.periode_end')
                ->orderBy('lab_tanggal', 'DESC');

            if ($nomorRM) {
                $result = $result->where("transaksis.pasien_nomor_rm", "=", $nomorRM);
            }

            $result = $result->get();

            if ($result->count() > 0) {
                return $this->success($result);
            } else {
                return $this->error("Data tidak ditemukan");
            }
        } else {
            return $this->error("Error");
        }
    }


    public function bayar(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'kasir_no_reg' => 'required',
            'kasir_no_trans' => 'required',
            'pasien_nomor_rm' => 'required',
            'pasien_nama' => 'required',
            'pasien_alamat' => 'required',
            'pasien_kota' => 'required',
            'kasir_biaya' => 'required',
            'kasir_potongan' => 'required',
            'kasir_asuransi' => 'required',
            'kasir_jumlah' => 'required',
            'kasir_tunai' => 'required',
            'kasir_kartu' => 'required',
            'kasir_pribadi' => 'required',
            'kasir_bon_karyawan' => 'required',
            'kasir_keterangan' => 'required',
            'prsh_kode' => 'required',
            'kasir_pot_kode' => 'required',
            'kasir_pot_ket' => 'required',
            'kasir_kartu_kode' => 'required',
            'kasir_kartu_nama' => 'required',
            'kasir_kartu_nomor' => 'required',
            'kasir_atas_nama' => 'required',
            'kasir_bon_kode' => 'required',
            'kasir_bon_nama' => 'required',
            'kasir_bon_keterangan' => 'required',
            'kasir_status' => 'required',
            'user_id' => 'required',
            'kasir_jenis_bayar' => 'required',
            'metode_pembayaran' => 'required',
            'user_mobile_id' => 'required',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $prefix = 'KWJ-16';
        $nomor = $prefix . '-' . str_pad(KasirJalan::count() + 1, 6, '0', STR_PAD_LEFT);
        $current_date = date('Y-m-d H:i:s');

        $bayar = KasirJalan::create([
            'kasir_nomor' => $nomor,
            'kasir_no_reg' => $request->kasir_no_reg,
            'kasir_no_trans' => $request->kasir_no_trans,
            'kasir_tanggal' => $current_date,
            'trans_tanggal' => $current_date,
            'pasien_nomor_rm' => $request->pasien_nomor_rm,
            'pasien_nama' => $request->pasien_nama,
            'pasien_alamat' => $request->pasien_alamat,
            'pasien_kota' => $request->pasien_kota,
            'unit_kode' => 'LJ',
            'kasir_biaya' => $request->kasir_biaya,
            'kasir_potongan' => $request->kasir_potongan,
            'kasir_asuransi' => $request->kasir_asuransi,
            'kasir_jumlah' => $request->kasir_jumlah,
            'kasir_tunai' => $request->kasir_tunai,
            'kasir_kartu' => $request->kasir_kartu,
            'kasir_pribadi' => $request->kasir_pribadi,
            'kasir_bon_karyawan' => $request->kasir_pribadi,
            'kasir_keterangan' => 'Transaksi Via Mobile',
            'prsh_kode' => $request->prsh_kode,
            'kasir_pot_kode' => $request->kasir_pot_kode,
            'kasir_pot_ket' => $request->kasir_pot_ket,
            'kasir_kartu_kode' => $request->kasir_kartu_kode,
            'kasir_kartu_nama' => $request->kasir_kartu_nama,
            'kasir_kartu_nomor' => $request->kasir_kartu_nomor,
            'kasir_atas_nama' => $request->kasir_atas_nama,
            'kasir_bon_kode' => $request->kasir_bon_kode,
            'kasir_bon_nama' => $request->kasir_bon_nama,
            'kasir_bon_keterangan' => $request->kasir_bon_keterangan,
            'kasir_status' => 0,
            'user_id' => $request->user_id,
            'user_date' => $current_date,
            'user_shift' => $request->user_shift,
            'id_client' => 'H002',
            'kasir_jenis_bayar' => 1,
            'metode_pembayaran' => $request->metode_pembayaran,
            'user_mobile_id' => $request->user_mobile_id,
        ]);

        if ($bayar->save()) {
            return $this->success($bayar);
        } else {
            return $this->error("Gagal menyimpan pembayaran");
        }
    }

    public function notifikasi(Request $request)
    {

        $mobile = $request->input('user_mobile_id');

        if (!$mobile) {
            $data = Transaksi::all();
            return $this->success($data);
        } else {
            $query = Transaksi::query();

            if ($mobile) {
                $query->where('user_mobile_id', '=', $mobile);
            }

            $data = $query->select('user_mobile_id', 'lab_nomor', 'pasien_nama',  'pasien_nomor_rm', 'lab_cetak_status as status')->get();

            $detail = TransaksiDetail::query()->select('lab_nomor', 'lab_nama', 'lab_tarif')->whereIn('lab_nomor', $data->pluck('lab_nomor'))->get();


            $datas = [];
            foreach ($data as $d) {
                $details = $detail->where('lab_nomor', $d->lab_nomor);
                $detailData = [];
                foreach ($details as $s) {
                    $detailData[] = [
                        'lab_nomor' => $s->lab_nomor,
                        'lab_nama' => $s->lab_nama,
                        'lab_tarif' => $s->lab_tarif
                    ];
                };

                $datas[] = [
                    'user_mobile_id' => $d->user_mobile_id,
                    'lab_nomor' => $d->lab_nomor,
                    'pasien_nama' => $d->pasien_nama,
                    'pasien_nomor_rm' => $d->pasien_nomor_rm,
                    'status' => $d->status,
                    'detail' => $detailData,
                ];
            };

            if ($data->count() > 0) {
                return $this->success($datas);
            } else {
                return $this->error("Transaksi belum dibayar");
            }
        }
    }

    public function history(Request $request)
    {

        $mobile = $request->input('user_mobile_id');

        if (!$mobile) {
            $data = Transaksi::all();
            return $this->success($data);
        } else {
            $query = Transaksi::query();

            if ($mobile) {
                $query->where('user_mobile_id', '=', $mobile);
            }

            $data = $query->select('user_mobile_id', 'lab_nomor', 'lab_tanggal', 'dokter_nama', 'lab_jam_sample', 'pasien_gender', 'pasien_nama',  'pasien_nomor_rm', 'pasien_alamat', 'pasien_kota', 'lab_cetak_status as status', 'lab_jumlah', 'pasien_umur_thn', 'pasien_umur_bln', 'pasien_gender')->get();

            $detail = TransaksiDetail::query()
                ->leftJoin('lab_hasils', 'lab_hasils.lab_nomor', 'transaksi_details.lab_nomor')
                ->leftJoin('lab_references', 'lab_references.lab_kode', 'lab_hasils.lab_kode')
                ->select('transaksi_details.lab_nomor', 'lab_kode_detail', 'transaksi_details.lab_nama', 'lab_tarif', 'lab_hasils.lab_hasil', 'lab_hasils.lab_keterangan', 'lab_references.ref_value')
                ->whereIn('transaksi_details.lab_nomor', $data->pluck('lab_nomor'))
                ->get();

            $data->each(function ($item) use ($detail) {
                $item->detail = $detail->where('lab_nomor', $item->lab_nomor);
            });

            $datas = [];
            foreach ($data as $d) {
                $details = $detail->where('lab_nomor', $d->lab_nomor);
                $detailData = [];
                foreach ($details as $s) {
                    $detailData[] = [
                        'lab_nomor' => $s->lab_nomor,
                        'lab_nama' => $s->lab_nama,
                        'lab_tarif' => $s->lab_tarif,
                        'lab_hasil' => $s->lab_hasil,
                        'ref_value' => $s->ref_value,
                        'lab_keterangan' => $s->lab_keterangan
                    ];
                };

                $datas[] = [
                    'user_mobile_id' => $d->user_mobile_id,
                    'pasien_nomor_rm' => $d->pasien_nomor_rm,
                    'lab_tanggal' => $d->lab_tanggal,
                    'lab_nomor' => $d->lab_nomor,
                    'pasien_nama' => $d->pasien_nama,
                    'pasien_umur_thn' => $d->pasien_umur_thn,
                    'pasien_gender' => $d->pasien_gender,
                    'pasien_alamat' => $d->pasien_alamat,
                    'dokter_nama' => $d->dokter_nama,
                    'status' => $d->status,
                    'detail' => $detailData,
                ];
            };

            if ($data->count() > 0) {
                return $this->success($datas);
            } else {
                return $this->error("Transaksi belum dibayar");
            }
        }
    }


    public function status(Request $request)
    {

        $mobile = $request->input('user_mobile_id');
        $nomorRM = $request->input('pasien_nomor_rm');

        if (!$mobile &&  !$nomorRM) {
            $status = Transaksi::all();
            return $this->success($status);
        } else {
            $query = Transaksi::query();

            if ($mobile) {
                $query->where('user_mobile_id', '=', $mobile);
            }

            if ($nomorRM) {
                $query->where('pasien_nomor_rm', '=', $nomorRM);
            }

            $status = $query->get();

            if ($status->count() > 0) {
                return $this->success("Transaksi sudah dibayar");
            } else {
                return $this->error("Transaksi belum dibayar");
            }
        }
    }


    public function success($data, $message = "Berhasil")
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error($message)
    {
        return response()->json([
            'status' => 'failed',
            'code' => 400,
            'message' => $message
        ], 400);
    }
}
