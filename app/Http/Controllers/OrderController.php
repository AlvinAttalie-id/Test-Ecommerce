<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\MidtransService;

class OrderController extends Controller
{

    public function create(Product $product)
    {
        return view('order.create', compact('product'));
    }

    public function store(Request $request, MidtransService $midtrans)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $subtotal = $product->price * $request->quantity;

        // Buat Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_price' => $subtotal,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
        ]);

        // Detail Order
        DetailOrder::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        // === Jika Pembayaran via Midtrans ===
        if ($request->payment_method === 'midtrans') {
            $payload = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . now()->timestamp,
                    'gross_amount' => $subtotal,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'item_details' => [[
                    'id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $request->quantity,
                    'name' => $product->product_name,
                ]],
            ];

            $snap = $midtrans->createTransaction($payload);

            // Simpan Payment Midtrans
            Payment::create([
                'order_id' => $order->id,
                'payment_date' => now(),
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
            ]);

            // Redirect ke Midtrans Snap URL
            return redirect($snap->redirect_url);
        }

        // === Jika bukan Midtrans (transfer / cod) ===
        Payment::create([
            'order_id' => $order->id,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function history(Request $request)
    {
        $query = Order::with(['details.product', 'payment'])
            ->where('user_id', Auth::id())
            ->orderBy('order_date', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(5)->withQueryString();

        return view('transactions.history', compact('orders'));
    }
}
