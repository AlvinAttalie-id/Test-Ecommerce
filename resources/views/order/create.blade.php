<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Checkout</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl p-6 mx-auto bg-white rounded shadow">
            <h3 class="mb-4 text-xl font-bold">Produk: {{ $product->product_name }}</h3>
            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Jumlah:</label>
                    <input type="number" name="quantity" min="1" value="1"
                        class="w-full px-3 py-2 border rounded">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Alamat Pengiriman:</label>
                    <textarea name="shipping_address" class="w-full px-3 py-2 border rounded" rows="3"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Metode Pembayaran:</label>
                    <select name="payment_method" class="w-full px-3 py-2 border rounded">
                        <option value="transfer">Transfer Bank</option>
                        <option value="cod">Bayar di Tempat (COD)</option>
                    </select>
                </div>

                <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Konfirmasi
                    Pesanan</button>
            </form>
        </div>
    </div>
</x-app-layout>
