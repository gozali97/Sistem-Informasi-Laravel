<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjaminController extends Controller
{

    public function index(Request $request)
    {
        $prsh = $request->input('prsh');

        if (!$prsh) {
            $result = DB::table('perusahaans')->paginate(10);
            return $this->success($result);
        } elseif ($prsh) {
            $result = Perusahaan::query()
                ->select('perusahaans.prsh_jenis', 'perusahaans.prsh_kode', 'perusahaans.prsh_nama', 'perusahaans.perk_kode', 'perusahaans.id_client');
            if ($prsh) {
                $result = $result->where("prsh_kode", "=", $prsh);
            }

            $result = $result->where("prsh_status", "=", 'A')->get();

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
