<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">Daftar Produk</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filter Kategori -->
            <form method="GET" class="mb-6">
                <div class="flex items-center gap-3">
                    <label for="category_id" class="font-medium text-gray-700">Filter Kategori:</label>
                    <select name="category_id" id="category_id" onchange="this.form.submit()"
                        class="block w-64 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
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
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($products as $product)
                    <div
                        class="overflow-hidden transition duration-300 bg-white border shadow rounded-2xl hover:shadow-lg">
                        <img src="{{ asset('storage/' . $product->image_product) }}" alt="{{ $product->product_name }}"
                            class="object-cover w-full h-48">

                        <div class="p-4 space-y-2">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                            <p class="font-bold text-indigo-600 text-md">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</p>

                            <a href="{{ route('products.show', $product->id) }}"
                                class="inline-block px-4 py-2 mt-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Tidak ada produk tersedia.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
