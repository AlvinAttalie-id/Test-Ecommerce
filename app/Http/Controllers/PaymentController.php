<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function handleCallback(Request $request)
    {
        $notification = $request->all();

        $orderIdRaw = $notification['order_id'] ?? null;
        if (!$orderIdRaw) {
            return response()->json(['message' => 'Invalid callback'], 400);
        }

        preg_match('/ORDER-(\d+)-\d+/', $orderIdRaw, $matches);
        if (!isset($matches[1])) {
            return response()->json(['message' => 'Invalid order id format'], 400);
        }
        $orderId = $matches[1];

        $order = Order::with('details.product')->find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = $order->payment;
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $status = $notification['transaction_status'] ?? 'pending';

        switch ($status) {
            case 'capture':
            case 'settlement':
                $paymentStatus = 'paid';
                $orderStatus = 'success';

                // ✅ Kurangi stok produk
                foreach ($order->details as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->decrement('qty', $detail->quantity);
                    }
                }
                break;

            case 'pending':
                $paymentStatus = 'pending';
                $orderStatus = 'pending';
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $paymentStatus = 'failed';
                $orderStatus = 'failed';
                break;

            default:
                $paymentStatus = 'pending';
                $orderStatus = 'pending';
                break;
        }

        $payment->payment_status = $paymentStatus;
        $payment->save();

        $order->status = $orderStatus;
        $order->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
