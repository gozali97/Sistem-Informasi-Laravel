<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMobile;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{

    public function send(Request $request)
    {
        $user = UserMobile::where('email', $request->email)->first();
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email sent']);
    }

    public function verify(Request $request)
    {
        $user = UserMobile::find($request->id);
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'code' => 404,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'failed',
                'code' => 400,
                'message' => 'Email sudah diverifikasi sebelumnya'
            ], 400);
        }
        $user->markEmailAsVerified();
        return response()->json([
            'status' => 'success',
            'message' => 'Email berhasil diverifikasi',
            'data' => $user
        ]);
    }

    public function resend(Request $request)
    {
        $user = UserMobile::find($request->id);
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'code' => 404,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'failed',
                'code' => 400,
                'message' => 'Email sudah diverifikasi sebelumnya'
            ], 400);
        }
        $user->sendEmailVerificationNotification();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Verifikasi email berhasil dikirim ulang'
            ], 200);
    }

}
