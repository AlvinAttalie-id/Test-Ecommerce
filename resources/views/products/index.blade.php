<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftar Produk</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <form method="GET" class="mb-4">
                <label for="category_id">Filter Kategori:</label>
                <select name="category_id" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($products as $product)
                    <div class="p-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <img src="{{ asset('storage/' . $product->image_product) }}" alt=""
                            class="object-cover w-full h-48">
                        <h3 class="mt-2 text-lg font-bold">{{ $product->product_name }}</h3>
                        <p class="text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="text-blue-600 underline">Lihat
                            Detail</a>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
