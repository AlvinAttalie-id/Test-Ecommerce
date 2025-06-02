<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            Keranjang Belanja
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-xl rounded-xl">
                @if (session('success'))
                    <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 mb-4 text-red-700 bg-red-100 border border-red-300 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if (count($cart) === 0)
                    <p class="text-gray-500">Keranjang kamu kosong.</p>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-sm font-semibold text-gray-700 border-b">
                                <th class="p-3">Produk</th>
                                <th class="p-3 text-center">Harga</th>
                                <th class="p-3 text-center">Jumlah</th>
                                <th class="p-3 text-center">Subtotal</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @php $total = 0; @endphp
                            @foreach ($cart as $item)
                                @php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ $item['name'] }}</td>
                                    <td class="p-3 text-center">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">{{ $item['quantity'] }}</td>
                                    <td class="p-3 text-center">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        <form action="{{ route('order.cart.remove', $item['id']) }}" method="POST"
                                            onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="p-3 text-lg font-bold text-right">Total:</td>
                                <td colspan="2" class="p-3 text-lg font-bold text-left text-indigo-600">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('order.checkout') }}"
                            class="px-6 py-3 font-semibold text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                            Lanjut ke Checkout
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
