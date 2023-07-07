<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BcaController extends Controller
{
    public function getVirtualAccountStatus($corpID, $account, $date)
    {
        $accessToken = $this->getAccessToken();
        $apiKey = $this->getApiKey();
        $timestamp = date('Y-m-d\TH:i:s\Z');
        $relativeUrl = '/banking/v3/corporates/' . $corpID . '/accounts/' . $account . '/statements?EndDate=' . $date . '&StartDate=' . $date;
        $signature = $this->generateSignature($relativeUrl, $timestamp);

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
            'X-BCA-Key: ' . $apiKey,
            'X-BCA-Timestamp:' . $timestamp,
            'X-BCA-Signature: ' . $signature,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.klikbca.com' . $relativeUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    private function generateSignature($relativeUrl, $timestamp)
    {
        $secretKey = 'YourSecretKey';
        $signature = hash_hmac('sha256', $relativeUrl . $timestamp, $secretKey, true);
        $signatureBase64 = base64_encode($signature);

        return $signatureBase64;
    }

    public function getAccessToken()
    {
        $clientId = 'your_client_id';
        $clientSecret = 'your_client_secret';
        $url = 'https://api.klikbca.com/api/oauth/token';

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret)
        ];

        $data = http_build_query([
            'grant_type' => 'client_credentials'
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        $accessToken = $response['access_token'];
    }

    public function getApiKey()
    {
        $clientId = 'your_client_id';
        $clientSecret = 'your_client_secret';
        $url = 'https://api.klikbca.com/api/oauth/token';

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret)
        ];

        $data = http_build_query([
            'grant_type' => 'client_credentials'
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        $apiKey = $response['access_token'];

        return $apiKey;
    }
}
