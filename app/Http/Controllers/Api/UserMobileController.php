<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\VerificationEmail;
use App\Models\UserMobile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Pasien;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class UserMobileController extends Controller
{

    // use AuthenticatesUsers;

    public function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6|',
        ]);

        if ($validasi->fails()) {
            return $this->error($validasi->errors()->first());
        }

        $user = UserMobile::where('email', $request->email)->first();

        if ($user) {
            if (password_verify($request->password, $user->password)) {
                return $this->success($user);
            } else {
                return $this->error('Password salah');
            }
        }
        return $this->error('Email tidak terdaftar');
    }

    public function register(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'no_ktp' => 'required|min:16|max:17',
            'email' => 'required',
            'telepon' => 'required|min:11|max:13',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'domisili' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            return $this->error($validasi->errors()->first());
        }

        DB::beginTransaction();

        $status = 'Aktif';
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d H:i:s');

        try {
            $user = UserMobile::create([
                'nama_lengkap' => $request->nama_lengkap,
                'no_ktp' => $request->no_ktp,
                'email' => $request->email,
                'email_token' => Str::random(40),
                'telepon' => $request->telepon,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'domisili' => $request->domisili,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'password' => bcrypt($request->password),
                'status_user' => $status,
                'create_date' => $date,
                'create_by' => "null",
                'update_date' => $date,
                'update_by' => "null"
            ]);

            if ($user->save()) {

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

                $pasien = Pasien::create([
                    'pasien_nomor_rm' => 'H' . $year . $month . $day . $newNumber,
                    'user_mobile_id' => $user->id,
                    'pasien_nama' => $user->nama_lengkap,
                    'pasien_gender' => $user->jenis_kelamin,
                    'pasien_alamat' => $user->alamat,
                    'pasien_kelurahan' => $request->pasien_kelurahan,
                    'pasien_kecamatan' => $request->pasien_kecamatan,
                    'pasien_kota' => $request->pasien_kota,
                    'pasien_prov' => $request->pasien_prov,
                    'pasien_rt' => $request->pasien_rt,
                    'pasien_rw' => $request->pasien_rw,
                    'pasien_wilayah' => $request->pasien_wilayah,
                    'pasien_telp' => $user->telepon,
                    'pasien_hp' => $request->pasien_hp,
                    'pasien_tmp_lahir' => $user->tempat_lahir,
                    'pasien_tgl_lahir' => $user->tanggal_lahir,
                    'pasien_kerja_kode' => $request->pasien_kerja_kode,
                    'pasien_kerja' => $request->pasien_kerja,
                    'pasien_kerja_alamat' => $request->pasien_kerja_alamat,
                    'pasien_gol_darah' => $request->pasien_gol_darah,
                    'pasien_agama' => $user->agama,
                    'pasien_pdk' => $request->pasien_pdk,
                    'pasien_status_kw' => $request->pasien_status_kw,
                    'pasien_klg_nama' => $request->pasien_klg_nama,
                    'pasien_klg_kerja' => $request->pasien_klg_kerja,
                    'pasien_klg_pdk' => $request->pasien_klg_pdk,
                    'pasien_klg_hub' => $request->pasien_klg_hub,
                    'pasien_no_id' => $user->no_ktp,
                    'member_nomor' => $request->member_nomor,
                    'pasien_jenis' => $request->pasien_jenis,
                    'pasien_tgl_input' => $date,
                    'pasien_panggilan' => $request->pasien_panggilan,
                    'pasien_prioritas' => $request->pasien_prioritas,
                    'pasien_klg_tlp' => $request->pasien_klg_tlp,
                    'pasien_klg_alamat' => $request->pasien_klg_alamat,
                    'id_client' => "H002",
                    'pasien_perusahaan' => $request->pasien_perusahaan,
                    'pasien_divisi' => $request->pasien_divisi,
                    'pasien_email' => $user->email,
                    'pasien_kontak_darurat' => $request->pasien_kontak_darurat,
                    'pasien_catatan' => $request->pasien_catatan,
                    'pasien_title' => $request->pasien_title,
                    'pasien_foto' => $request->pasien_foto,
                ]);

                if ($pasien->save()) {
                    $user->sendEmailVerificationNotification();
                    DB::commit();
                    return $this->success($user);
                } else {
                    return $this->error("Gagal membuat data Pasien");
                }
            } else {
                return $this->error("Registrasi gagal");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            return $this->error($errorMessage);
        }
    }


    public function show(Request $request)
    {
        $user_id = $request->input('user_mobile_id');

        $user = UserMobile::where('id', $user_id)->first();
        if (!$user) {
            return $this->error("Data tidak ditemukan");
        }

        return $this->success($user);
    }


    public function changePassword(Request $request)
    {
        $id = $request->input('id');

        $rules = array(
            'old_password' => 'required',
            'id' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $input = $request->all();
        $validator = Validator::make($input, $rules);
        $user = UserMobile::query()->where('id', $id)->first();
        if ($validator->fails()) {
            $arr = array("status" => "failed", "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), $user->password)) == false) {
                    $arr = array(
                        "status" => "failed",
                        "message" => "Periksa kembali password lama anda.",
                    );
                } else if ((Hash::check(request('new_password'), $user->password)) == true) {
                    $arr = array(
                        "status" => "failed",
                        "message" => "Tolong masukkan password yang berbeda dengan password lama.",
                    );
                } else {
                    UserMobile::where('id', $id)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array(
                        "status" => "success",
                        "message" => "Password berhasil diubah.",
                        "data" => array([
                            'email' => $user->email,
                            'nama' => $user->nama_lengkap
                        ])
                    );
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 'gagal', "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
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
