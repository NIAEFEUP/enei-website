<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Create a new order and initiate a payment.
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'total' => 'required|numeric',
            'phone_number' => 'required|string',
            'email' => 'nullable|email|max:100',
        ]);

        // Create the order in the database
        $order = Order::create([
            'participant_id' => $request->participant_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'state' => 'Unitialized',
            'phone_number' => $request->phone_number,
        ]);

        if($request->total > 500)
        {
            return response()->json([
                'order' => $order,
                'message' => 'Total amount exceeds the limit of 500.00',
            ], 400);
        }

        // Prepare payment data
        $mbWayKey = env('IFTHENPAY_MBWAY_KEY');
        $mobileNumber = '351#' . $request->phone_number; // Add country code prefix
        $data = [
            'mbWayKey' => $mbWayKey,
            'orderId' => $order->id,
            'amount' => number_format($order->total, 2, '.', ''), // Ensure correct decimal format
            'mobileNumber' => $mobileNumber,
            'email' => $request->email ?? null,
            'description' => 'ENEI Payment for order #' . $order->id,
        ];

        // Make API request to initiate MB WAY payment
        $response = Http::post('https://api.ifthenpay.com/spg/payment/mbway', $data);

        if ($response->successful()) {
            $responseData = $response->json();
            $order->request_id = $responseData['RequestId'];
            $order->state = 'Pending';
            $order->save();

            return response()->json([
                'order' => $order,
                'requestId' => $responseData['RequestId'],
                'message' => $responseData['Message'],
                'status' => $responseData['Status'],
            ]);
        } else {
            return response()->json([
                'order' => $order,
                'message' => 'Failed to initiate payment. Please try again later.',
            ], 500);
        }
    }

    /**
     * Retrieve an order and check its payment status if requestId is provided.
     */
    public function getOrder(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Check payment status if `requestId` is provided
        if ($order->id) {
            $mbWayKey = env('IFTHENPAY_MBWAY_KEY');
            $requestId = $order->request_id;

            $response = Http::get("https://api.ifthenpay.com/spg/payment/mbway/status", [
                'mbWayKey' => $mbWayKey,
                'requestId' => $requestId,
            ]);
   

            if ($response->successful()) {
                $statusData = $response->json();
    

                // Update order state based on payment status
                $paymentStatus = $statusData['Status'];
                $stateMap = [
                    '000' => 'Success',
                    '020' => 'Rejected',
                    '101' => 'Expired',
                    '122' => 'Declined',
                ];

                $order->state = $stateMap[$paymentStatus] ?? 'Pending';
                $order->save();

                return response()->json([
                    'order' => $order,
                    'paymentStatus' => $statusData,
                ]);
            } else {
                return response()->json([
                    'order' => $order,
                    'message' => 'Failed to check payment status',
                ], 500);
            }
        }

        // Return order without payment status
        return response()->json($order);
    }
}
