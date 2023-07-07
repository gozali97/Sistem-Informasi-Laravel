<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlamatHomecare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    public function index(Request $request){
        $mobileID = $request->input('user_mobile_id');
        if ($mobileID) {
        $result = AlamatHomecare::query()
            ->select('alamat_homecares.*');

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
    public function store(Request $request){
        $validasi = Validator::make($request->all(), [
            'user_mobile_id'=> 'required',
            'nama_lengkap'=> 'required',
            'nomor_rumah'=> 'required',
            'detail_alamat'=> 'required',
            'alamat'=> 'required',
            'longitude'=> 'required',
            'latitude'=> 'required',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }
        $date = date('Y-m-d H:i:s');

        $alamat = AlamatHomecare::create([
            'user_mobile_id'=> $request->user_mobile_id,
            'nama_lengkap'=> $request->nama_lengkap,
            'nomor_rumah'=> $request->nomor_rumah,
            'detail_alamat'=> $request->detail_alamat,
            'alamat'=> $request->alamat,
            'longitude'=> $request->longitude,
            'latitude'=> $request->latitude,
            'create_date' => $date,
            'update_date' => $date
        ]);

        if ($alamat->save()) {
            return $this->success($alamat);
        }else{
            return $this->error("Gagal menyimpan Alamat");
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
