<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Mail\Message;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validasi->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Validasi Error',
                'errors' => $validasi->errors(),
                'content' => null,
            ];
            return response()->json($respon, 200);
        } else {
            $credentials = request(['username', 'password']);
            
            if (!Auth::attempt($credentials)) {
                $respon = [
                    'status' => 'error',
                    'msg' => 'Unathorized',
                    'errors' => null,
                    'content' => null,
                ];
                return response()->json($respon, 401);
            }
            $user = User::where('username', $request->username)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception('Error in login');
            }
            $tokenResult = $user->createToken('token-auth')->planTextToken;
            $respon = [
                'status' => 'Success',
                'msg' => 'Login Sukses',
                'errors' => null,
                'content' => [
                    'status_code' => 200,
                    'acces_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ],
            ];
            return response()->json($respon, 200);
        }
    }

    public function register(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            return $this->error($validasi->errors()->first());
        }

        $user = User::create(array_merge($request->all(), [
            'password' => bcrypt($request->password)
        ]));

        if ($user) {
            return $this->success($user);
        } else {
            return $this->error('Registrasi gagal');
        }
    }

    public function forgot_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
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
