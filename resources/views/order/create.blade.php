<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Checkout</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl p-6 mx-auto bg-white shadow-lg rounded-xl sm:p-8">
            <h3 class="mb-6 text-xl font-semibold text-gray-900">
                Pesan Produk: <span class="text-blue-600">{{ $product->product_name }}</span>
            </h3>

            <form action="{{ route('order.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Quantity --}}
                <div>
                    <label for="quantity" class="block mb-1 text-sm font-medium text-gray-700">Jumlah <span
                            class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" required
                        class="w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Alamat Pengiriman --}}
                <div>
                    <label for="shipping_address" class="block mb-1 text-sm font-medium text-gray-700">Alamat Pengiriman
                        <span class="text-red-500">*</span></label>
                    <textarea name="shipping_address" id="shipping_address" rows="3" required
                        class="w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm resize-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label for="payment_method" class="block mb-1 text-sm font-medium text-gray-700">Metode Pembayaran
                        <span class="text-red-500">*</span></label>
                    <select name="payment_method" id="payment_method" required
                        class="w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>-- Pilih Metode --</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="cod">Bayar di Tempat (COD)</option>
                        <option value="midtrans">Bayar via Midtrans</option>
                    </select>
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full px-4 py-2 font-semibold text-white transition duration-200 bg-blue-600 rounded-lg shadow hover:bg-blue-700">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
@endpush
