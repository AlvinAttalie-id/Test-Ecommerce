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
        // Ambil data callback dari Midtrans
        $notification = $request->all();

        // Contoh data yang dikirim Midtrans:
        // $notification['transaction_status'], $notification['order_id'], dll.

        $orderIdRaw = $notification['order_id'] ?? null;
        if (!$orderIdRaw) {
            return response()->json(['message' => 'Invalid callback'], 400);
        }

        // Misal order_id format: ORDER-{id}-{timestamp}
        // Ambil id asli dari order
        preg_match('/ORDER-(\d+)-\d+/', $orderIdRaw, $matches);
        if (!isset($matches[1])) {
            return response()->json(['message' => 'Invalid order id format'], 400);
        }
        $orderId = $matches[1];

        // Cari Order & Payment terkait
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = $order->payment; // Asumsi relasi order->payment sudah dibuat
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $status = $notification['transaction_status'] ?? 'pending';

        // Update status payment dan order berdasarkan status Midtrans
        switch ($status) {
            case 'capture':
            case 'settlement':
                $paymentStatus = 'paid';
                $orderStatus = 'success';
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

        // Update Payment
        $payment->payment_status = $paymentStatus;
        $payment->save();

        // Update Order
        $order->status = $orderStatus;
        $order->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
