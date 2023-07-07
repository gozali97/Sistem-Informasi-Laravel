<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function bcaPayment(Request $request)
    {
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://apibeta.klikbca.com/api/payment/inquiry-payment', $data);

        $redirectUrl = $response->json()['RedirectUrl'];

        return redirect($redirectUrl);
    }
    public function bcaCallback(Request $request)
    {
        $data = [
            'MerchantCode' => 'your-merchant-code',
            'Terminal' => 'your-terminal',
            'TransactionNo' => $request->TransactionNo,
            'TransactionAmount' => $request->TransactionAmount,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://apibeta.klikbca.com/api/payment/inquiry-status', $data);

        $status = $response->json()['TransactionStatus'];

        if ($status === 'Success') {
            // Payment Successful
            return redirect('/payment-success');
        } else {
            // Payment Failed
            return redirect('/payment-failed');
        }
    }
}

