<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Riwayat Transaksi</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @forelse ($orders as $order)
                <div class="p-6 mb-6 bg-white rounded shadow">
                    <h3 class="mb-2 text-lg font-semibold">Tanggal:
                        {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }}</h3>
                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                    <p class="mb-1"><strong>Alamat Pengiriman:</strong> {{ $order->shipping_address }}</p>

                    <h4 class="mt-4 font-semibold">Detail Produk:</h4>
                    <ul class="list-disc list-inside">
                        @foreach ($order->details as $item)
                            <li>{{ $item->product->product_name }} (x{{ $item->quantity }}) - Rp
                                {{ number_format($item->price, 0, ',', '.') }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        <p><strong>Pembayaran:</strong> {{ $order->payment->payment_method }}
                            ({{ ucfirst($order->payment->payment_status) }})</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">Belum ada transaksi.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
