<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            {{-- Filter Status --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('transactions.history') }}" class="flex items-center gap-4">
                    <label for="status" class="text-sm font-medium text-gray-700">Filter Status:</label>
                    <select name="status" id="status" onchange="this.form.submit()"
                        class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </form>
            </div>

            {{-- Daftar Order --}}
            @forelse ($orders as $order)
                @php
                    $statusClasses = [
                        'pending' => 'bg-amber-100 text-amber-800',
                        'paid' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'default' => 'bg-gray-100 text-gray-800',
                    ];

                    $orderStatusClass = $statusClasses[$order->status] ?? $statusClasses['default'];
                    $paymentStatusClass = $statusClasses[$order->payment->payment_status] ?? $statusClasses['default'];
                @endphp

                <div class="p-6 mb-6 transition bg-white border border-gray-200 shadow rounded-2xl hover:shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }}
                        </h3>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $orderStatusClass }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="space-y-1 text-sm text-gray-600">
                        <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <p><strong>Alamat Pengiriman:</strong> {{ $order->shipping_address }}</p>
                    </div>

                    <div class="mt-4">
                        <h4 class="mb-2 font-semibold text-gray-700">Detail Produk:</h4>
                        <ul class="space-y-1 text-sm text-gray-600 list-disc list-inside">
                            @foreach ($order->details as $item)
                                <li>
                                    {{ $item->product->product_name }} (x{{ $item->quantity }}) -
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex items-center mt-4 space-x-2 text-sm text-gray-600">
                        <span><strong>Pembayaran:</strong> {{ $order->payment->payment_method }}</span>
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $paymentStatusClass }}">
                            {{ ucfirst($order->payment->payment_status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Belum ada transaksi.</p>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
