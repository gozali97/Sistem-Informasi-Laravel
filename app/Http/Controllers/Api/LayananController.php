<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\TarifVar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LayananController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $grupJenis = $request->input('grup_jenis');

        $grup = Group::query();
        if ($grupJenis) {
            $grup = $grup->where("grup_jenis", "=",  $grupJenis);
        }

        $result = $grup->get();
        if (!$result->isEmpty()) {
            return $this->success($result);
        }

        return $this->error("Data tidak ditemukan");
    }

    public function search(Request $request)
    {
        $varNama = $request->input('var_nama');
        $tarifNama = $request->input('tarif_nama');
        $paketNama = $request->input('paket_nama');
        $paketlab = $request->input('paket_kode');
        $dateNow = Carbon::now();

        if (!$varNama && !$tarifNama && !$paketNama) {
            $result = TarifVar::query()
                ->join('tarif_labs', 'tarif_labs.tarif_kelompok', '=', 'tarif_vars.var_kode')
                ->join('paket_hubungs', 'paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode')
                ->join('paket_labs', 'paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
                ->select('tarif_vars.*', 'tarif_labs.*', DB::raw("case when( tarif_labs.periode_start <= '" . $dateNow . "' AND tarif_labs.periode_end >= '" . $dateNow . "' and paket_labs.periode_start <= '" . $dateNow . "' AND paket_labs.periode_end >= '" . $dateNow . "') then tarif_labs.tarif_jalan-tarif_labs.promo_value
                else 0 end AS HargaPromo"))
                ->where("tarif_vars.var_seri", "=", "LAB")->get();

            return $this->success($result);
        } elseif ($varNama || $tarifNama || $paketNama) {
            $result = TarifVar::query()
                ->join('tarif_labs', 'tarif_labs.tarif_kelompok', '=', 'tarif_vars.var_kode')
                ->join('paket_hubungs', 'paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode')
                ->join('paket_labs', 'paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
                ->select('tarif_vars.var_seri', 'tarif_vars.var_kode', 'tarif_vars.var_nama', 'tarif_vars.path_gambar', 'paket_hubungs.paket_kode', 'paket_labs.paket_kelompok', 'paket_labs.paket_nama', 'paket_labs.paket_jalan', 'paket_labs.paket_status', 'paket_labs.paket_periode', 'paket_labs.paket_diskon', 'paket_labs.path_gambar', 'paket_labs.deskripsi', 'paket_labs.catatan', 'paket_labs.manfaat', 'tarif_labs.tarif_kelompok', 'tarif_vars.var_nama', 'tarif_labs.tarif_nama', 'tarif_labs.tarif_jalan', 'tarif_labs.path_gambar as lab_gambar', 'tarif_labs.promo_value', 'tarif_labs.promo_percent', 'tarif_labs.fix_value', 'tarif_labs.deskripsi as lab_dekripsi', 'tarif_labs.catatan as lab_catatan', 'tarif_labs.manfaat as lab_manfaat', 'tarif_labs.periode_start', 'tarif_labs.periode_end', DB::raw("case when( tarif_labs.periode_start <= '" . $dateNow . "' AND tarif_labs.periode_end >= '" . $dateNow . "' and paket_labs.periode_start <= '" . $dateNow . "' AND paket_labs.periode_end >= '" . $dateNow . "') then tarif_labs.tarif_jalan-tarif_labs.promo_value
                else 0 end AS HargaPromo"))
                ->distinct()
                ->where("tarif_vars.var_seri", "=", "LAB");

            if ($varNama) {
                $result = $result->where("var_nama", "=", $varNama);
            }

            if ($tarifNama) {
                $result = $result->where("tarif_nama", "=", $tarifNama);

                if ($paketlab) {
                    $result = $result->where("paket_kode", "=", $paketlab);
                }

                if ($paketNama) {
                    $result = $result->where("paket_nama", "like", '%' . $paketNama . '%');
                }

                $result = $result->get();

                if ($result->count() > 0) {
                    return $this->success($result);
                } else {
                    return $this->error("Data tidak ditemukan");
                }
            } else {
                return $this->error("Data tidak ditemukan");
            }
        }
    }

    public function detail(Request $request)
    {
        $tarifNama = $request->input('tarif_nama');
        $tarifKelmpk = $request->input('tarif_kelompok');
        $dateNow = Carbon::now();

        if (!$tarifNama && !$tarifKelmpk) {
            return $this->error("Data tidak ditemukan");
        } elseif ($tarifNama) {
            $result = TarifVar::query()
                ->join('tarif_labs', 'tarif_labs.tarif_kelompok', 'tarif_vars.var_kode')
                ->join('paket_hubungs', 'paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode')
                ->join('paket_labs', 'paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
                ->select('tarif_vars.var_seri', 'tarif_labs.tarif_kelompok', 'tarif_vars.var_nama', 'tarif_labs.tarif_nama', 'tarif_labs.tarif_jalan', 'tarif_labs.path_gambar', 'tarif_labs.promo_value', 'tarif_labs.promo_percent', 'tarif_labs.fix_value', 'tarif_labs.deskripsi', 'tarif_labs.catatan', 'tarif_labs.manfaat', 'tarif_labs.periode_start', 'tarif_labs.periode_end', DB::raw("case when( tarif_labs.periode_start <= '" . $dateNow . "' AND tarif_labs.periode_end >= '" . $dateNow . "' and paket_labs.periode_start <= '" . $dateNow . "' AND paket_labs.periode_end >= '" . $dateNow . "') then tarif_labs.tarif_jalan-tarif_labs.promo_value
                else 0 end AS HargaPromo"))
                ->distinct()
                ->where("var_seri", "=", "LAB");
            if ($tarifNama) {
                $result = $result->where("tarif_labs.tarif_nama", "like", "%" . $tarifNama . "%");
            }

            $result = $result->whereRaw('case when( tarif_labs.periode_start <= ? AND tarif_labs.periode_end >= ? and paket_labs.periode_start <= ? AND paket_labs.periode_end >= ?) then tarif_labs.tarif_jalan-tarif_labs.promo_value
        else 0 end > 0', [$dateNow, $dateNow, $dateNow, $dateNow])->get();

            if ($result->count() > 0) {
                return $this->success($result);
            } else {
                return $this->error("Data tidak ditemukan");
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
