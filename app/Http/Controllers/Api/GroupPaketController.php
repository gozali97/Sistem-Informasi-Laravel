<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupPaket;
use App\Models\PaketLab;
use App\Models\TarifLab;
use App\Models\TarifVar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupPaketController extends Controller
{

    public function index()
    {

        $paket = GroupPaket::query()
        ->select('group_pakets.*')
        ->get();

        if (!$paket->isEmpty()) {

            $detail = PaketLab::whereIn('paket_labs.paket_kelompok',$paket->pluck('paket_kelompok'))->get();


            $paket = $paket->map(function ($item) use ($detail) {
                $item->detail = collect($detail)->filter(function ($detailItem) use ($item) {
                    return $detailItem['paket_kelompok'] == $item->paket_kelompok;
                })->values();
                return $item;
            });

            $paket = $paket->filter(function ($item) {
                return $item->detail->isNotEmpty();
            });

            if ($paket->isNotEmpty()) {
                return $this->success($paket);
            }
        }

        return $this->error("Data tidak ditemukan");
    }

    public function itemLayanan(Request $request)
    {
        $data = $request->get('data');
        if (!$data) {
            $result = PaketLab::all();
            return $this->success($result);
        } elseif ($data) {
            $result = PaketLab::query();
            if (isset($data['paket_nama'])) {
                $result = $result->where("paket_nama", "like", "%" . $data['paket_nama'] . "%");
            }

            if (isset($data['paket_kelompok'])) {
                $result = $result->where("paket_kelompok", "=", $data['paket_kelompok']);
            }

            $result = $result->get();

            if (!$result->isEmpty()) {
                return $this->success($result);
            } else {
                return $this->error("Data tidak ditemukan");
            }
        } else {
            return $this->error("Error");
        }
    }

    public function filter(Request $request)
    {
        $tarifKlmpk = $request->input('tarif_kelompok');
        $varnama = $request->input('var_nama');
        $tarifNma = $request->input('tarif_nama');
        $paketNma = $request->input('paket_nama');

        $dateNow = Carbon::now();

        if (!$tarifKlmpk && !$varnama && !$tarifNma && !$paketNma) {
            $result = TarifVar::query()
                ->join('tarif_labs', 'tarif_labs.tarif_kelompok', '=', 'tarif_vars.var_kode')
                ->join('paket_hubungs', 'paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode')
                ->join('paket_labs', 'paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
                ->select('tarif_vars.*', 'tarif_labs.*', DB::raw("case when( tarif_labs.periode_start <= '" . $dateNow . "' AND tarif_labs.periode_end >= '" . $dateNow . "' and paket_labs.periode_start <= '" . $dateNow . "' AND paket_labs.periode_end >= '" . $dateNow . "') then tarif_labs.tarif_jalan-tarif_labs.promo_value
                else 0 end AS HargaPromo"))
                ->where("tarif_vars.var_seri", "=", "LAB")->get();
            return $this->success($result);
        } elseif ($tarifNma || $paketNma) {
            $result = TarifVar::query()
                ->join('tarif_labs', 'tarif_labs.tarif_kelompok', '=', 'tarif_vars.var_kode')
                ->join('paket_hubungs', 'paket_hubungs.tarif_kode', '=', 'tarif_labs.tarif_kode')
                ->join('paket_labs', 'paket_labs.paket_kode', '=', 'paket_hubungs.paket_kode')
                ->select('tarif_vars.var_seri', 'tarif_vars.var_seri', 'tarif_vars.var_nama', 'paket_labs.paket_kode', 'paket_labs.paket_kelompok', 'paket_labs.paket_nama', 'paket_labs.paket_jalan', 'paket_labs.paket_status', 'paket_labs.paket_periode', 'paket_labs.paket_diskon', 'paket_labs.path_gambar', 'paket_labs.deskripsi', 'paket_labs.catatan', 'paket_labs.manfaat', 'tarif_labs.tarif_kelompok', 'tarif_labs.tarif_nama', 'tarif_labs.tarif_jalan', 'tarif_labs.path_gambar', 'tarif_labs.promo_value', 'tarif_labs.promo_percent', 'tarif_labs.fix_value', 'tarif_labs.deskripsi', 'tarif_labs.catatan', 'tarif_labs.manfaat', 'tarif_labs.periode_start', 'tarif_labs.periode_end', DB::raw("case when( tarif_labs.periode_start <= '" . $dateNow . "' AND tarif_labs.periode_end >= '" . $dateNow . "' and paket_labs.periode_start <= '" . $dateNow . "' AND paket_labs.periode_end >= '" . $dateNow . "') then tarif_labs.tarif_jalan-tarif_labs.promo_value
                else 0 end AS HargaPromo"))
                ->where("tarif_vars.var_seri", "=", "LAB");

            // if ($tarifKlmpk) {
            //     $result = $result->where("var_kode", "=", $tarifKlmpk);
            // }
            // if ($varnama) {
            //     $result = $result->where("var_nama", "=", $varnama);
            // }

            if ($tarifNma) {
                $result = $result->where("tarif_nama", "=", $tarifNma);
            }

            if ($paketNma) {
                $result = $result->where("paket_nama", "=", $paketNma);
            }

            $filter = $result->get();

            if ($filter->count() > 0) {
                return $this->success($filter);
            } else {
                return $this->error("Data tidak ditemukan");
            }
        } else {
            return $this->error("Gagal");
        }
    }


    public function detail(Request $request)
    {
        $paketgroup = $request->input('group_paket_id');
        $paketKelompok = $request->input('paket_kelompok');

        if (!$paketKelompok && !$paketgroup ) {
            return $this->error("Data tidak ditemukan");
        }elseif ($paketgroup) {
            $result = GroupPaket::query()
            ->join('paket_labs','paket_labs.paket_kelompok', 'group_pakets.paket_kelompok')
            ->select('group_pakets.group_paket_id','group_pakets.paket_kelompok','group_pakets.nama_paket', 'paket_labs.paket_kode', 'paket_labs.paket_nama', 'paket_labs.paket_jalan','paket_labs.paket_diskon','paket_labs.path_gambar','paket_labs.deskripsi','paket_labs.catatan','paket_labs.manfaat');

        if ($paketgroup) {
            $result = $result->where("group_pakets.group_paket_id", "like", "%" . $paketgroup . "%");
        }
        if ($paketKelompok) {
            $result = $result->where("paket_labs.paket_kelompok", "like", "%" . $paketKelompok . "%");
        }

        $result = $result->distinct()->get();

        foreach ($result as $group) {
            $item = TarifLab::query()
                ->join('paket_hubungs','paket_hubungs.tarif_kode', 'tarif_labs.tarif_kode')
                ->join('paket_labs','paket_labs.paket_kode', 'paket_hubungs.paket_kode')
                ->select('tarif_labs.tarif_kode', 'tarif_labs.tarif_nama as lab_nama','tarif_labs.tarif_jalan as lab_tarif', 'tarif_labs.promo_value as lab_diskon' ,'tarif_labs.promo_percent as lab_diskon_prs', 'tarif_labs.fix_value as lab_jumlah', 'tarif_labs.tarif_jalan as lab_pribadi')
                ->where('paket_labs.paket_kode', $group->paket_kode)
                ->distinct()
                ->get();

            $group->item = $item;
        }

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
            'status' => "success",
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error($message)
    {
        return response()->json([
            'status' => "failed",
            'code' => 400,
            'message' => $message
        ], 400);
    }
}
