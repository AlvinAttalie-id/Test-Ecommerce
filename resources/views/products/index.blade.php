<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-slate-800">🛍️ Daftar Produk</h2>
    </x-slot>

    <div class="min-h-screen py-10 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filter Kategori -->
            <form method="GET" class="mb-8">
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <label for="category_id" class="text-base font-medium text-slate-700">Filter Kategori</label>
                    <select name="category_id" id="category_id" onchange="this.form.submit()"
                        class="w-full px-4 py-2 transition bg-white border rounded-lg shadow-sm sm:w-64 border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Produk Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
                @forelse ($products as $product)
                    <div
                        class="overflow-hidden transition duration-300 bg-white shadow rounded-xl hover:shadow-lg group">
                        <!-- Gambar -->
                        <div class="relative overflow-hidden aspect-square">
                            <img src="{{ asset('storage/' . $product->image_product) }}"
                                alt="{{ $product->product_name }}"
                                class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
                            <!-- Badge Stok -->
                            <span
                                class="absolute top-2 left-2 px-3 py-1 rounded-full text-xs font-semibold
                                {{ $product->qty > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' }}">
                                {{ $product->qty > 0 ? 'Sisa: ' . $product->qty : 'Stok Habis' }}
                            </span>
                        </div>

                        <!-- Informasi Produk -->
                        <div class="flex flex-col justify-between p-4 h-44">
                            <div>
                                <h3 class="text-lg font-semibold truncate text-slate-800">{{ $product->product_name }}
                                </h3>
                                <p class="mt-1 font-bold text-indigo-600">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>

                            @if ($product->qty > 0)
                                <a href="{{ route('products.show', $product->id) }}"
                                    class="inline-block px-4 py-2 mt-4 text-sm font-medium text-center text-white transition bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                    Lihat Detail
                                </a>
                            @else
                                <button disabled
                                    class="w-full px-4 py-2 mt-4 text-sm font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-lg text-center text-gray-500 col-span-full">Tidak ada produk tersedia.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links('vendor.pagination.custom-tailwind') }}
            </div>

        </div>
    </div>
</x-app-layout>
