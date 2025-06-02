<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-slate-800">🧾 Riwayat Transaksi</h2>
    </x-slot>

    <div class="min-h-screen py-8 bg-gray-50">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            {{-- Filter Status --}}
            <form method="GET" action="{{ route('transactions.history') }}" class="flex items-center gap-3 mb-6">
                <label for="status" class="text-base font-medium text-slate-700">Filter Status</label>
                <select name="status" id="status" onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </form>

            @forelse ($orders as $order)
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'default' => 'bg-gray-100 text-gray-800',
                    ];
                    $statusBadge = $statusColors[$order->status] ?? $statusColors['default'];
                    $paymentBadge = $statusColors[$order->payment->payment_status] ?? $statusColors['default'];
                @endphp

                <div class="mb-6 transition bg-white border border-gray-200 shadow rounded-xl hover:shadow-md">
                    <div class="flex flex-col p-5 border-b md:flex-row md:justify-between md:items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">
                                🧾 Pesanan #{{ $order->id }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                Status: {{ ucfirst($order->status) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $paymentBadge }}">
                                {{ ucfirst($order->payment->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 space-y-3 text-sm text-gray-700">
                        <p><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment->payment_method) }}</p>
                        <p><strong>Alamat Pengiriman:</strong> {{ $order->shipping_address }}</p>
                        <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

                        <details class="p-4 mt-2 border rounded-md bg-gray-50">
                            <summary class="text-sm font-medium text-indigo-600 cursor-pointer hover:underline">
                                Lihat Detail Produk
                            </summary>
                            <ul class="mt-3 space-y-2 list-disc list-inside">
                                @foreach ($order->details as $item)
                                    <li class="flex items-center gap-3">
                                        @if ($item->product && $item->product->image_product)
                                            <img src="{{ asset('storage/' . $item->product->image_product) }}"
                                                alt="{{ $item->product->product_name }}"
                                                class="object-cover w-12 h-12 border rounded" />
                                        @endif
                                        <span>
                                            <strong>{{ $item->product->product_name }}</strong>
                                            (x{{ $item->quantity }})
                                            -
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p class="text-lg text-gray-500">Belum ada transaksi.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
