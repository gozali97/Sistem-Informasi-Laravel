<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMobile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


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
            //            'user_mobile_id' => 'required',
            'nama_lengkap' => 'required',
            'no_ktp' => 'required|min:16|max:17',
            'email' => 'required',
            'telepon' => 'required|min:11|max:13',
            'password' => 'required|min:6'
        ]);


        if ($validasi->fails()) {
            return $this->error($validasi->errors()->first());
        }

        $status = 'Aktif';
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d H:i:s');

        $user = UserMobile::create([
            'nama_lengkap' => $request->nama_lengkap,
            'no_ktp' => $request->no_ktp,
            'email' => $request->email,
            'email_token' => Str::random(40),
            'telepon' => $request->telepon,
            'password' => bcrypt($request->password),
            'status_user' => $status,
            'create_date' => $date,
            'create_by' => "null",
            'update_date' => $date,
            'update_by' => "null"
        ]);

        if ($user->save()) {
            $user->sendEmailVerificationNotification();
            return $this->success($user);
        } else {
            return $this->error("Registrasi gagal");
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
