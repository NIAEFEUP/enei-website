<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class PaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $data = [
            'mbWayKey' => env('IFTHENPAY_MBWAY_KEY'),
            'orderId' => $request->orderId,  
            'amount' => $request->amount,    // Amount as a string (e.g. 100.50)
            'mobileNumber' => $request->mobileNumber,
            'email' => $request->email,      // Optional
            'description' => $request->description,  // Optional
        ];

        // Send the request to Ifthenpay API
        $response = Http::post('https://api.ifthenpay.com/spg/payment/mbway', $data);

        $responseData = $response->json();

        if ($response->successful()) {
            return response()->json([
                'requestId' => $responseData['RequestId'],
                'message' => $responseData['Message'],
                'status' => $responseData['Status']
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to initiate payment',
            ], 500);
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        $mbWayKey = env('IFTHENPAY_MBWAY_KEY');
        $requestId = $request->requestId; // Get the requestId from the frontend or database

        $response = Http::get("https://api.ifthenpay.com/spg/payment/mbway/status", [
            'mbWayKey' => $mbWayKey,
            'requestId' => $requestId
        ]);

        $statusData = $response->json();

        if ($response->successful()) {
            return response()->json($statusData);
        } else {
            return response()->json(['message' => 'Failed to fetch payment status'], 500);
        }
    }

}
