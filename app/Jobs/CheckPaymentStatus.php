<?php

namespace App\Jobs;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckPaymentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;
    protected $requestId;

    public function __construct($orderId, $requestId)
    {
        $this->orderId = $orderId;
        $this->requestId = $requestId;
    }

    public function handle()
    {   
        Log::info('Starting CheckPaymentStatus job.', [
            'orderId' => $this->orderId,
            'requestId' => $this->requestId,
        ]);
        $order = Order::find($this->orderId);
        Log::debug("Checking payment status for order: {id}", ['id' => $this->orderId]);

        if ($order) {
            $mbWayKey = env('IFTHENPAY_MBWAY_KEY');
            $response = Http::get("https://api.ifthenpay.com/spg/payment/mbway/status", [
                'mbWayKey' => $mbWayKey,
                'requestId' => $this->requestId,
            ]);

            if ($response->successful()) {
                $statusData = $response->json();
                $paymentStatus = $statusData['Status'];


                $stateMap = [
                    '000' => 'Success',
                    '020' => 'Rejected',
                    '101' => 'Expired',
                    '122' => 'Declined',
                ];

                // Update the order status based on the payment status
                $order->state = $stateMap[$paymentStatus] ?? 'Pending';
                $order->save();
            }
        }
    }
}
