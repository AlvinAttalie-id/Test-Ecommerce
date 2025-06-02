<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">💳 Checkout</h2>
    </x-slot>

    <div class="min-h-screen py-10 bg-gray-50">
        <div class="max-w-5xl px-4 mx-auto">
            <div class="p-6 bg-white shadow-lg rounded-2xl">
                @if (count($cart) === 0)
                    <div class="text-center text-gray-500">Keranjang kamu kosong.</div>
                @else
                    <form action="{{ route('order.store.cart') }}" method="POST">
                        @csrf

                        <h3 class="mb-6 text-xl font-semibold">🧾 Ringkasan Pesanan</h3>
                        <div class="mb-8 space-y-4">
                            @php $total = 0; @endphp
                            @foreach ($cart as $item)
                                @php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <div class="flex items-center justify-between pb-4 border-b">
                                    <div class="flex items-center gap-4">

                                        <div>
                                            <p class="font-medium">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-500">Jumlah: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <div class="font-semibold text-indigo-600">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                            <div class="pt-2 text-lg font-bold text-right text-indigo-700">
                                Total: Rp {{ number_format($total, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="shipping_address" class="block mb-1 font-medium text-gray-700">Alamat
                                Pengiriman</label>
                            <textarea name="shipping_address" id="shipping_address" required rows="3"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">{{ old('shipping_address') }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label for="payment_method" class="block mb-1 font-medium text-gray-700">Metode
                                Pembayaran</label>
                            <select name="payment_method" id="payment_method" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                                <option value="cod">Bayar di Tempat (COD)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="midtrans">Midtrans</option>
                            </select>
                        </div>

                        @php $firstItem = collect($cart)->first(); @endphp
                        @if (count($cart) === 1 && $firstItem)
                            <input type="hidden" name="product_id" value="{{ $firstItem['id'] }}">
                            <input type="hidden" name="quantity" value="{{ $firstItem['quantity'] }}">
                        @endif

                        <div class="text-right">
                            <button type="submit"
                                class="px-6 py-3 font-semibold text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                                Bayar Sekarang
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
