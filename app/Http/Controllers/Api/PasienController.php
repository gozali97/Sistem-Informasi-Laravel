<?php

namespace App\Http\Controllers\Api;

use DateTime;

use App\Models\UserMobile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admvar;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use Illuminate\Support\Facades\Validator;
use App\Models\Logbook;
use App\Models\Pasien;
use App\Models\Provinsi;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{

    public function index()
    {
        $pasien = Pasien::all();
        return $this->success($pasien);
    }

    // public function createNewRegNumber()
    // {
    //     $now = new DateTime();
    //     $year = $now->format('y');
    //     $month = $now->format('m');
    //     $day = $now->format('d');
    //     $lastPasien = Pasien::orderBy('pasien_nomor_rm', 'desc')->first();

    //     if ($lastPasien == null) {
    //         $newNumber = '001';
    //     } else {
    //         $lastLabNumber = $lastPasien->pasien_nomor_rm;
    //         $lastYear = substr($lastLabNumber, 3, 2);
    //         $lastMonth = substr($lastLabNumber, 5, 2);
    //         $lastDay = substr($lastLabNumber, 7, 2);
    //         $lastNumber = (int)substr($lastLabNumber, -4);

    //         if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
    //             $newNumber = '001';
    //         } else {
    //             $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    //         }
    //     }
    //     return 'H' . $year . $month . $day . $newNumber;
    // }

    public function store(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'pasien_nama'         => 'required',
            'pasien_gender'       => 'required',
            'pasien_alamat'       => 'required',
            'pasien_kelurahan'    => 'required',
            'pasien_kecamatan'    => 'required',
            'pasien_kota'         => 'required',
            'pasien_prov'         => 'required',
            'pasien_rt'           => 'required',
            'pasien_rw'           => 'required',
            'pasien_wilayah'      => 'required',
            'pasien_telp'         => 'required',
            'pasien_hp'           => 'required',
            'pasien_tmp_lahir'     => 'required',
            'pasien_tgl_lahir'     => 'required',
            'pasien_kerja'        => 'required',
            'pasien_agama'        => 'required',
            'pasien_kerja_kode'    => 'required',
            'pasien_kerja_alamat'  => 'required',
            'pasien_gol_darah'     => 'required',
            'pasien_pdk'          => 'required',
            'pasien_status_kw'     => 'required',
            'pasien_klg_nama'      => 'required',
            'pasien_klg_tlp'      => 'required',
            'pasien_kontak_darurat' => 'required',
            'pasien_klg_alamat'    => 'required',
            'pasien_title'        =>  'required',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $now = new DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastPasien = Pasien::orderBy('pasien_nomor_rm', 'desc')->first();


        if ($lastPasien == null) {
            $newNumber = '001';
        } else {
            $lastLabNumber = $lastPasien->pasien_nomor_rm;
            $lastYear = substr($lastLabNumber, 1, 2);
            $lastMonth = substr($lastLabNumber, 3, 2);
            $lastDay = substr($lastLabNumber, 5, 2);
            $lastNumber = (int)substr($lastLabNumber, -3);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $newNumber = '001';
            } else {
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        }


        DB::beginTransaction();
        date_default_timezone_set('Asia/Jakarta');
        $id = $request->input('user_mobile_id');
        $date = date('Y-m-d H:i:s');
        $user = UserMobile::find($id);

        $ip         = $_SERVER['REMOTE_ADDR'];


        try {

            $pasien = Pasien::create([
                'pasien_nomor_rm' => 'H' . $year . $month . $day . $newNumber,
                'user_mobile_id' => $request->user_mobile_id,
                'pasien_nama' => $request->pasien_nama,
                'pasien_gender' => $request->pasien_gender,
                'pasien_alamat' => $request->pasien_alamat,
                'pasien_kelurahan' => $request->pasien_kelurahan,
                'pasien_kecamatan' => $request->pasien_kecamatan,
                'pasien_kota' => $request->pasien_kota,
                'pasien_prov' => $request->pasien_prov,
                'pasien_rt' => $request->pasien_rt,
                'pasien_rw' => $request->pasien_rw,
                'pasien_wilayah' => $request->pasien_wilayah,
                'pasien_telp' => $request->pasien_telp,
                'pasien_hp' => $request->pasien_hp,
                'pasien_tmp_lahir' => $request->pasien_tmp_lahir,
                'pasien_tgl_lahir' => $request->pasien_tgl_lahir,
                'pasien_kerja_kode' => $request->pasien_kerja_kode,
                'pasien_kerja' => $request->pasien_kerja,
                'pasien_kerja_alamat' => $request->pasien_kerja_alamat,
                'pasien_gol_darah' => $request->pasien_gol_darah,
                'pasien_agama' => $request->pasien_agama,
                'pasien_pdk' => $request->pasien_pdk,
                'pasien_status_kw' => $request->pasien_status_kw,
                'pasien_klg_nama' => $request->pasien_klg_nama,
                'pasien_klg_kerja' => $request->pasien_klg_kerja,
                'pasien_klg_pdk' => $request->pasien_klg_pdk,
                'pasien_klg_hub' => $request->pasien_klg_hub,
                'pasien_no_id' => $request->pasien_no_id,
                'member_nomor' => $request->member_nomor,
                'pasien_jenis' => $request->pasien_jenis,
                'pasien_tgl_input' => $date,
                // 'user_id' => $request->user_id,
                'pasien_panggilan' => $request->pasien_panggilan,
                'pasien_prioritas' => $request->pasien_prioritas,
                'pasien_klg_tlp' => $request->pasien_klg_tlp,
                'pasien_klg_alamat' => $request->pasien_klg_alamat,
                'id_client' => "H002",
                'pasien_perusahaan' => $request->pasien_perusahaan,
                'pasien_divisi' => $request->pasien_divisi,
                // 'pasien_posisi' => $request->pasien_posisi,
                // 'no_mcu' => $request->no_mcu,
                'pasien_email' => $request->pasien_email,
                'pasien_kontak_darurat' => $request->pasien_kontak_darurat,
                'pasien_catatan' => $request->pasien_catatan,
                'pasien_title' => $request->pasien_title,
            ]);

            $logbook = Logbook::create([
                'user_mobile_id'  => $pasien->user_mobile_id,
                'log_ip_address'  => $ip,
                'log_date'       => $date,
                'log_menu_no'     => $request->log_menu_no,
                'log_menu_nama'   => $request->log_menu_nama,
                'log_jenis'      => $request->log_jenis,
                'log_no_bukti'    => $request->log_no_bukti,
                'log_keterangan' => 'Daftar Pasien a/n ' . $pasien->pasien_nama,
                'log_jumlah' => $request->log_jumlah,
                'id_client'  => $pasien->id_client,
                'user_id' =>  $pasien->user_mobile_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            return $this->error($errorMessage);
        }
        DB::commit();

        return $this->success($pasien);
    }

    public function view(Request $request)
    {
        $id = $request->input('user_mobile_id');

        $pasien = Pasien::query()
            ->join('user_mobiles', 'user_mobiles.id', 'pasiens.user_mobile_id')
            ->join('admvars', 'admvars.var_kode', 'pasiens.pasien_agama')
            ->select('pasien_no_id as ktp', 'pasien_nomor_rm', 'pasien_nama', 'pasien_gender', 'pasien_alamat', 'pasien_wilayah', 'pasien_hp', 'pasien_tmp_lahir', 'pasien_tgl_lahir', 'pasien_hp', 'var_nama as agama')
            ->where('pasiens.user_mobile_id', $id)
            ->where('admvars.var_seri', 'AGAMA')
            ->get();

        if ($pasien->count() > 0) {
            return $this->success($pasien);
        }
        return $this->error("Data tidak ditemukan");
    }


    public function show(Request $request)
    {
        $id = $request->input('pasien_nomor_rm');
        if ($id) {
            $pasien = Pasien::query()
                ->where('pasien_nomor_rm', $id)
                ->get();

            if ($pasien->count() > 0) {
                return $this->success($pasien);
            } else {
                return $this->error('Data tidak ada');
            }
        }
        return $this->error('Data tidak ditemukan');
    }

    public function verifByKtp(Request $request)
    {

        $noKTP = $request->input('no_ktp');

        if (!$noKTP) {
            return $this->error("Masukkan no KTP dulu");
        } else {
            $pasien = Pasien::query()->where('pasien_no_id', '=', $noKTP)->get();

            if (!$pasien->isEmpty()) {
                // Validasi no KTP
                if (preg_match('/^62\d{14}$/', $noKTP)) {
                    return $this->success($pasien);
                } else {
                    return $this->error("No KTP tidak valid, no KTP harus berjumlah 16 karakter, diawali dengan angka 62");
                }
            } else {
                return $this->error("gagal");
            }
        }
    }

    public function getProv()
    {
        $prov = Provinsi::select('provinsi.prov_id', 'prov_name')->get();

        if ($prov->count() > 0) {
            return $this->success($prov);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getKota(Request $request)
    {

        $prov = $request->input('prov_id');

        $kota = Kota::select('kota.city_id', 'city_name')
            ->where('prov_id', $prov)
            ->get();

        if ($kota->count() > 0) {
            return $this->success($kota);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getKecamatan(Request $request)
    {

        $kota = $request->input('city_id');

        $kecamatan = Kecamatan::select('dis_id', 'dis_name')
            ->where('city_id', $kota)
            ->get();

        if ($kecamatan->count() > 0) {
            return $this->success($kecamatan);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getKelurahan(Request $request)
    {

        $kecamatan = $request->input('dis_id');

        $kelurahan = Kelurahan::select('subdis_id', 'subdis_name')
            ->where('dis_id', $kecamatan)
            ->get();

        if ($kelurahan->count() > 0) {
            return $this->success($kelurahan);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getKerja()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'PEKERJAAN')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getDarah()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'DARAH')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getPendidikan()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'PENDIDIKAN')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getAgama()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'AGAMA')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getJenis()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'JENISPAS')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getStatusKw()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'KAWIN')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getHubKel()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'KELUARGA')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function getTitle()
    {

        $data = Admvar::select('var_kode', 'var_nama')
            ->where('var_seri', 'TITLE')
            ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        }
        return $this->error("Data tidak ditemukan");
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
