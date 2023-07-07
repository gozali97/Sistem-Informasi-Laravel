<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\LabHasil;
use App\Models\Perusahaan;
use App\Models\TarifLab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    public function index(){

        $data = Group::query()
                ->where('grup_jenis', 'PRSH')
                ->select('groups.grup_kode', 'groups.grup_nama')
                ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        } else {
            return $this->error("Data tidak ditemukan");
        }

    }

    public function pilihPrsh(){

        $data = Perusahaan::query()
                ->where('prsh_kode', 'LIKE', '1-%')
                ->select('perusahaans.prsh_kode', 'perusahaans.prsh_nama')
                ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        } else {
            return $this->error("Data tidak ditemukan");
        }

    }

    public function pilihRs(){

        $data = Perusahaan::query()
                ->where('prsh_kode', 'LIKE', '2-%')
                ->select('perusahaans.prsh_kode', 'perusahaans.prsh_nama')
                ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        } else {
            return $this->error("Data tidak ditemukan");
        }

    }

    public function pilihDokter(){

        $data = Perusahaan::query()
                ->where('prsh_kode', 'LIKE', '3-%')
                ->select('perusahaans.prsh_kode', 'perusahaans.prsh_nama')
                ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        } else {
            return $this->error("Data tidak ditemukan");
        }

    }

    public function pilihLembaga(){

        $data = Perusahaan::query()
                ->where('prsh_kode', 'LIKE', '4-%')
                ->select('perusahaans.prsh_kode', 'perusahaans.prsh_nama')
                ->get();

        if ($data->count() > 0) {
            return $this->success($data);
        } else {
            return $this->error("Data tidak ditemukan");
        }

    }

    public function hasil(Request $request)
    {
        $nomorRM = $request->input('pasien_nomor_rm');
        $mobileID = $request->input('user_mobile_id');
        $noLab = $request->input('lab_nomor');

        if (!$nomorRM && !$mobileID && !$noLab) {
            $result = LabHasil::all();
            return $this->success($result);
        } elseif ($nomorRM || $mobileID || $noLab) {
            $result = LabHasil::query()
                ->join('pasiens', 'pasiens.pasien_nomor_rm', '=', 'lab_hasils.pasien_nomor_rm')
                ->join('user_mobiles', 'user_mobiles.id', '=', 'lab_hasils.user_mobile_id')
                ->select('lab_hasils.*', 'pasiens.*', 'user_mobiles.*');

            if ($nomorRM) {
                $result = $result->where("lab_hasils.pasien_nomor_rm", "=", $nomorRM);
            }

            if ($mobileID) {
                $result = $result->where("lab_hasils.user_mobile_id", "=", $mobileID);
            }

            if ($noLab) {
                $result = $result->where("lab_hasils.lab_nomor", "=", $noLab);
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

    public function promo()
    {
        $dateNow = Carbon::now();
        $result = TarifLab::query()
            ->join('paket_hubungs', 'paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode')
            ->join('paket_labs', 'paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
            ->where('paket_labs.paket_periode', '>=', $dateNow)
            ->where('paket_labs.periode_start', '<=', $dateNow)
            ->where('paket_labs.periode_end', '>=', $dateNow);
        $result = $result->get();
        if ($result->count() > 0) {
            return $this->success($result);
        } else {
            return $this->error("Data tidak ditemukan");
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
