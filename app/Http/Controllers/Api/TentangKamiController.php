<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TentangKami;
use Illuminate\Http\Request;

class TentangKamiController extends Controller
{

    public function index()
    {
        $kami = TentangKami::query()
        ->select('judul', 'deskripsi', 'gambar')
        ->where('status', 1)->get();
        return $this->success($kami);
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
