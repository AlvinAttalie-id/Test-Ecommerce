<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
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

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_price' => $subtotal,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
        ]);

        DetailOrder::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        if ($request->payment_method === 'cod') {
            return redirect()->route('dashboard')->with('success', 'Pesanan COD berhasil dibuat. Menunggu konfirmasi admin.');
        }

        // Midtrans
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
        return redirect($snap->redirect_url);
    }

    public function cart()
    {
        $cart = Session::get('cart', []);
        return view('order.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => $product->price,
                'quantity' => $request->quantity,
            ];
        }

        Session::put('cart', $cart);
        return redirect()->route('order.cart')->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);
        return redirect()->route('order.cart')->with('success', 'Produk dihapus dari keranjang');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('order.cart')->with('error', 'Keranjang kosong. Tambahkan produk terlebih dahulu.');
        }

        return view('order.checkout', compact('cart'));
    }

    public function storeFromCart(Request $request, MidtransService $midtrans)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('order.cart')->with('error', 'Keranjang kosong.');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_price' => $subtotal,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
        ]);

        foreach ($cart as $item) {
            DetailOrder::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        Payment::create([
            'order_id' => $order->id,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        if ($request->payment_method === 'cod') {
            Session::forget('cart');
            return redirect()->route('dashboard')->with('success', 'Pesanan COD berhasil dibuat dan menunggu konfirmasi admin.');
        }

        $payload = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id . '-' . now()->timestamp,
                'gross_amount' => $subtotal,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => collect($cart)->values()->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'name' => $item['name'],
                ];
            })->toArray(),
        ];

        $snap = $midtrans->createTransaction($payload);
        Session::forget('cart');
        return redirect($snap->redirect_url);
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

    //Konfirmasi oleh Admin
    public function confirmOrder($id)
    {
        $order = Order::with(['details'])->findOrFail($id);

        if ($order->status === 'paid') {
            return back()->with('info', 'Pesanan sudah dibayar.');
        }

        $order->status = 'paid';
        $order->save();

        Payment::where('order_id', $order->id)->update([
            'payment_status' => 'paid'
        ]);

        foreach ($order->details as $detail) {
            Product::where('id', $detail->product_id)->decrement('qty', $detail->quantity);
        }

        return back()->with('success', 'Pesanan berhasil dikonfirmasi dan stok dikurangi.');
    }
}
