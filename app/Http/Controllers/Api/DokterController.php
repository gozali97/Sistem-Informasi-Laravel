<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index(){

        $data = Dokter::all();

        if ($data->count() > 0) {
            return $this->success($data);
        } else {
            return $this->error("Data tidak ditemukan");
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
