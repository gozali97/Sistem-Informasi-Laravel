<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SyaratKetentuan;
use Illuminate\Http\Request;

class SyaratKetentuanController extends Controller
{

    public function index()
    {
        $SK = SyaratKetentuan::query()->where('kategori_nama', 0)->select('judul_syarat_ketentuan', 'syarat_ketentuan_value')->first();
        // dd($SK);
        if ($SK->count() > 0) {
            return $this->success($SK);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function bayar()
    {
        $SK = SyaratKetentuan::query()->where('kategori_nama', 1)->select('judul_syarat_ketentuan', 'syarat_ketentuan_value')->first();

        if ($SK->count() > 0) {
            return $this->success($SK);
        }
        return $this->error("Data tidak ditemukan");
    }

    public function profil()
    {
        $SK = SyaratKetentuan::query()->where('kategori_nama', 2)->select('judul_syarat_ketentuan', 'syarat_ketentuan_value')->first();
        // dd($SK);
        if ($SK->count() > 0) {
            return $this->success($SK);
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
