<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\RawatJalan;
use App\Models\Transaksi;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RawatJalanCotroller extends Controller
{
    public function createNewRegNumber($i)
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
            // dd($lastLabNumber);
            $lastYear = substr($lastLabNumber, 3, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $lastNumber = 0;
            }
            // $lastNumber += $i;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
// if($i == 1){
//    dd($lastLabNumber);
// }
        }
        return 'RP-' . $year . $month . $day . '-' . $newNumber;

    }

    public function store(Request $request)
    {

        $data = $request->json()->all();

        DB::beginTransaction();

        try {

            $rawatJalans = [];
            $jalan_no_reg = array();

            for ($i = 0; $i < count($data['pasien']); $i++) {

                $jalan_nomor = $this->createNewRegNumber($i);

                array_push($jalan_no_reg, $jalan_nomor);

                $pasien = Pasien::query()
                    ->join('kota', 'kota.city_id', 'pasiens.pasien_kota')
                    ->where('pasien_nomor_rm', $data['pasien'][$i]['pasien_nomor_rm'])
                    ->first();

                if (!$pasien) {
                    return $this->error("Pasien tidak ditemukan");
                }

                $pasien_nama = $pasien->pasien_nama;
                $pasien_gender = $pasien->pasien_gender;
                $pasien_alamat = $pasien->pasien_alamat;
                $pasien_kota = $pasien->city_name;
                $tanggal_lahir = \Carbon\Carbon::createFromFormat('m/d/Y', $pasien->pasien_tgl_lahir)->startOfDay();
                $umur = $tanggal_lahir->diff(\Carbon\Carbon::now());
                $pasien_umur_hari = $umur->days;
                $pasien_umur_bulan = $umur->y * 12 + $umur->m;
                $pasien_umur_tahun = $umur->y;


                $rawatJalan = new RawatJalan;
                $rawatJalan->jalan_no_reg =  $jalan_nomor;
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
                $rawatJalan->jalan_no_urut = substr($jalan_no_reg[$i], -2);
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
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil',
                'data' => $rawatJalans
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show(Request $request)
    {
        $nomorRM = $request->input('pasien_nomor_rm');
        $mobileID = $request->input('user_mobile_id');

        if (!$nomorRM && !$mobileID) {
            return $this->error('Data tidak ada');
        } elseif ($nomorRM || $mobileID) {
            $result = RawatJalan::query()
                ->select('rawat_jalans.*');

            if ($nomorRM) {
                $result = $result->where("pasien_nomor_rm", "=", $nomorRM);
            }

            if ($mobileID) {
                $result = $result->where("user_mobile_id", "=", $mobileID);
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
