<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BankController extends Controller
{

    public function getBcaTransaction(Request $request)
    {
        $token = $this->getToken(); //get token from function or database
        $nominal = $request->nominal; //get nominal from request
        $AccessToken = $token->access_token;
        $api_key = $request->api_key;
        $signature = $request->signature;
        $date = $request->date;
        $tgl = $request->tgl;
        $dataMutasi = $request->dataMutasi;
        $bookingID = $request->bookingID;
        $trans = $request->trans;
        $relativeUrl = '/banking/v3/corporates/' . $token->corpID . '/accounts/' . $token->account . '/statements?EndDate=' . $date . '&StartDate=' . $date;
        $headers = [
            'Authorization' => 'Bearer ' . $AccessToken,
            'Content-Type' => 'application/json',
            'X-BCA-Key' => $api_key,
            'X-BCA-Timestamp' => $tgl,
            'X-BCA-Signature' => $signature,
        ];
        if (!$token || !$nominal) {
            return response()->json(['error' => 'token or nominal not found'], 400);
        }
        $client = new Client();
        $response = $client->get('https://apibeta.bca.co.id' . $relativeUrl, [
            'headers' => $headers,
        ]);
        $result = json_decode($response->getBody());
        foreach ($dataMutasi['Data'] as $row) {
            $amount = round($row['TransactionAmount']);
            if (round($nominal) == $amount) {
                $pos = strpos($row['Trailer'], 'TANGGAL :');
                $pos1 = strpos($row['Trailer'], 'TANGGAL :' . date('d/m'));
                if ($pos === false) {
                    $this->verifikasi($bookingID, "", $trans);
                } elseif ($pos1 >= 0 && $pos1 !== false) {
                    $this->verifikasi($bookingID, "", $trans);
                }
            }
        }
        return $result;
    }
}



    //     public function getBcaTransaction(Request $request)
    // {
    //     $access_token = $this->getAccessToken();
    //     $client = new Client();
    //     $response = $client->get('https://apibeta.bca.co.id/banking/v3/corporates/transactions', [
    //         'headers' => [
    //             'Authorization' => 'Bearer '.$access_token,
    //             'Content-Type' => 'application/json',
    //         ],
    //         'query' => [
    //             'start_date' => $request->start_date,
    //             'end_date' => $request->end_date,
    //             'account_number' => $request->account_number,
    //         ]
    //     ]);

    //     $result = json_decode($response->getBody());
    //     return $result;
    // }
