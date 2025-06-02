<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">
            🛒 Keranjang Belanja
        </h2>
    </x-slot>

    <div class="min-h-screen py-10 bg-gray-50">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-md rounded-2xl">
                {{-- Alerts --}}
                @if (session('success'))
                    <div class="flex items-center p-4 mb-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg"
                        role="alert">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="flex items-center p-4 mb-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg"
                        role="alert">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                @if (count($cart) === 0)
                    <div class="text-center text-gray-500">
                        Keranjang kamu kosong. Yuk, belanja dulu!
                    </div>
                @else
                    {{-- Cart Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700">
                            <thead class="text-xs text-gray-600 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Produk</th>
                                    <th class="px-6 py-4 text-center">Harga</th>
                                    <th class="px-6 py-4 text-center">Jumlah</th>
                                    <th class="px-6 py-4 text-center">Subtotal</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @php $total = 0; @endphp
                                @foreach ($cart as $item)
                                    @php
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item['name'] }}</td>
                                        <td class="px-6 py-4 text-center">Rp
                                            {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">{{ $item['quantity'] }}</td>
                                        <td class="px-6 py-4 font-semibold text-center text-indigo-600">Rp
                                            {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('order.cart.remove', $item['id']) }}" method="POST"
                                                onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="px-6 py-4 text-lg font-bold text-right">Total:</td>
                                    <td class="px-6 py-4 text-lg font-bold text-indigo-700">Rp
                                        {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Checkout Button --}}
                    <div class="flex justify-end mt-8">
                        <a href="{{ route('order.checkout') }}"
                            class="inline-flex items-center px-6 py-3 font-semibold text-white transition bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700">
                            Checkout Sekarang →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
