<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function create(Product $product)
    {
        return view('order.create', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $subtotal = $product->price * $request->quantity;

        // 1. Buat Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => Carbon::now(),
            'total_price' => $subtotal,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
        ]);

        // 2. Buat Detail Order
        DetailOrder::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        // 3. Buat Payment otomatis
        Payment::create([
            'order_id' => $order->id,
            'payment_date' => Carbon::now(),
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function history()
    {
        $orders = Order::with(['details.product', 'payment'])
            ->where('user_id', Auth::id())
            ->orderBy('order_date', 'desc')
            ->get();

        return view('transactions.history', compact('orders'));
    }
}
