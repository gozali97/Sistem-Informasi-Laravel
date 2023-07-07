<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessTokenController extends Controller
{
    public function generate(Request $request)
    {

        $timestamp = $request->header('X-TIMESTAMP');
        $clientKey = $request->header('X-CLIENT-KEY');
        $signature = $request->header('X-SIGNATURE');

        // Cek nilai dari "grantType" dalam request body
        $grantType = $request->input('grantType');

        // Validasi header dan request body
        if (!$timestamp || !$clientKey || !$signature || !$grantType) {
            return response()->json([
                'error' => 'Invalid request'
            ], 400);
        }

        // Cek apakah "grantType" yang diterima sesuai dengan yang diharapkan
        if ($grantType != 'client_credentials') {
            return response()->json([
                'error' => 'Invalid grant type'
            ], 400);
        }

        // Cek apakah "X-TIMESTAMP" mengikuti standar ISO-8601
        if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?(Z|[+-]\d{2}:\d{2})$/', $timestamp)) {
            return response()->json([
                'error' => 'Invalid timestamp'
            ], 400);
        }

        // Cek apakah "X-CLIENT-KEY" dan "X-SIGNATURE" valid
        if (!$this->validateClientKey($clientKey) || !$this->validateSignature($signature)) {
            return response()->json([
                'error' => 'Invalid client key or signature'
            ], 400);
        }

        // Generate token akses
        $accessToken = $this->generateAccessToken();

        // Kembalikan response dengan token akses
        return response()->json([
            'access_token' => $accessToken
        ], 200);
    }

    private function validateClientKey($clientKey)
    {
        // Logika untuk validasi "X-CLIENT-KEY"
        // ...
    }

    private function validateSignature($signature)
    {
        // Logika untuk validasi "X-SIGNATURE"
        // ...
    }

    private function generateAccessToken()
    {
     
        $token = Str::random(32);
        return $token;
    }
}
