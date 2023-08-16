<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rujukan;
use App\Models\RujukanDetail;
use App\Models\User;
use App\Models\UserMobile;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RujukanController extends Controller
{
    public function index()
    {
        $rujukan = Rujukan::all();

        if ($rujukan->count() < 0) {
            return $this->success($rujukan);
        } else {
            return $this->error("Data tidak ditemukan");
        }
    }

    public function createNewRujNumber()
    {
        $now = new DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = Rujukan::orderBy('rujukan_no', 'desc')->first();

        if ($lastTransaksi == null) {
            $newNumber = '0001';
        } else {
            $lastLabNumber = $lastTransaksi->rujukan_no;
            $lastYear = substr($lastLabNumber, 3, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $newNumber = '0001';
            } else {
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        }
        return 'RJ-' . $year . $month . $day . '-' . $newNumber;
    }

    public function store(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'nama_pasien' => 'required',
            'nama_dokter' => 'required',
            'rumah_sakit' => 'required',
            'pasien_nomor_rm' => 'required',
            'user_mobile_id' => 'required',
            'file_rujukan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validasi->fails()) {
            return $this->error($validasi->errors()->first());
        }

        DB::beginTransaction();

        try {

            $current_date = date('Y-m-d H:i:s');

            $file  = $request->pasien_nomor_rm . '-' . time() .  '.' . $request->file_rujukan->extension();
            $path       = $request->file('file_rujukan')->move('storage/rujukan', $file);


            $user  = UserMobile::where('id', $request->user_mobile_id)->first();
            $rujukan = Rujukan::create([
                'pasien_nomor_rm' => $request->pasien_nomor_rm,
                'user_mobile_id' => $request->user_mobile_id,
                'rujukan_no' => $this->createNewRujNumber(),
                'nama_pasien' => $request->nama_pasien,
                'file_rujukan' => 'https://staging-lis.k24.co.id/storage/rujukan/' . $file,
                'nama_dokter' => $request->nama_dokter,
                'rumah_sakit' => $request->rumah_sakit,
                'tanggal_transaksi' => $current_date,
                'status' => 1,
                'create_date' => $current_date,
                'create_by' => $user->nama_lengkap,
                'update_date' => $current_date,
                'update_by' => $user->nama_lengkap
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return $this->error('Register Gagal');
        }

        DB::commit();

        return $this->success($rujukan);
    }

    public function show(Request $request)
    {
        $nomorRM = $request->input('pasien_nomor_rm');
        $mobileID = $request->input('user_mobile_id');

        if (!$nomorRM && !$mobileID) {
            $result = Rujukan::all();
            return $this->success($result);
        } elseif ($nomorRM || $mobileID) {

            $result = Rujukan::query()
                ->join('pasiens', 'pasiens.pasien_nomor_rm', '=', 'rujukans.pasien_nomor_rm')
                ->join('user_mobiles', 'user_mobiles.id', '=', 'rujukans.user_mobile_id')
                ->select('rujukans.rujukan_no', 'pasiens.pasien_nomor_rm', 'rujukans.nama_pasien', 'rujukans.tanggal_transaksi', 'rujukans.nama_dokter', 'rujukans.rumah_sakit', 'rujukans.file_rujukan', 'rujukans.status', 'rujukans.create_date', 'rujukans.create_by')
                ->where("rujukans.status", 1);

            if ($nomorRM) {
                $result = $result->where("rujukans.pasien_nomor_rm", "=", $nomorRM);
            }

            if ($mobileID) {
                $result = $result->where("rujukans.user_mobile_id", "=", $mobileID);
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

    public function update(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'rujukan_no' => 'required',
            'nama_pasien' => 'required',
            'nama_dokter' => 'required',
            'rumah_sakit' => 'required',
            'pasien_nomor_rm' => 'required',
            'user_mobile_id' => 'required'
        ]);

        if ($validasi->fails()) {
            return $this->error($validasi->errors()->first());
        }

        $Rujukan = Rujukan::find($request->rujukan_id);
        \DB::beginTransaction();

        $status = "Aktif";
        $date = date('Y-m-d H:i:s');


        $Rujukan->rujukan_no = $request->rujukan_no;
        $Rujukan->nama_pasien = $request->nama_pasien;
        $Rujukan->nama_dokter = $request->nama_dokter;
        $Rujukan->rumah_sakit = $request->rumah_sakit;
        $Rujukan->tanggal_transaksi = $date;
        $Rujukan->status = $status;
        $Rujukan->create_date = $date;
        $Rujukan->update_date = $date;
        $Rujukan->pasiennomorrm = $request->pasiennomorrm;
        $Rujukan->user_mobile_id = $request->user_mobile_id;

        if ($Rujukan->save()) {
            \DB::commit();
            return $this->success($Rujukan);
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
