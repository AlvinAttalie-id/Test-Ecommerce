<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $product->product_name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl p-6 mx-auto bg-white rounded shadow sm:px-6 lg:px-8">
            {{-- Carousel --}}
            @if ($product->gallery_product && count($product->gallery_product) > 0)
                <div x-data="{ current: 0 }" class="relative mb-6">
                    <div class="overflow-hidden rounded-lg h-96">
                        <template x-for="(image, index) in {{ json_encode($product->gallery_product) }}"
                            :key="index">
                            <div x-show="current === index" class="w-full h-full">
                                <img :src="'/storage/' + image" alt="" class="object-cover w-full h-96">
                            </div>
                        </template>
                    </div>

                    <button
                        @click="current = (current === 0) ? {{ count($product->gallery_product) - 1 }} : current - 1"
                        class="absolute left-0 px-4 py-2 text-white -translate-y-1/2 bg-gray-800 bg-opacity-50 top-1/2">
                        ‹
                    </button>
                    <button
                        @click="current = (current === {{ count($product->gallery_product) - 1 }}) ? 0 : current + 1"
                        class="absolute right-0 px-4 py-2 text-white -translate-y-1/2 bg-gray-800 bg-opacity-50 top-1/2">
                        ›
                    </button>

                    {{-- Thumbnail indicators --}}
                    <div class="flex justify-center mt-2 space-x-2">
                        <template x-for="(image, index) in {{ json_encode($product->gallery_product) }}"
                            :key="index">
                            <button @click="current = index" :class="{ 'ring-2 ring-blue-500': current === index }"
                                class="w-16 h-16 overflow-hidden rounded hover:opacity-75 focus:outline-none">
                                <img :src="'/storage/' + image" class="object-cover w-full h-full">
                            </button>
                        </template>
                    </div>
                </div>
            @else
                <img src="{{ asset('storage/' . $product->image_product) }}" alt=""
                    class="object-cover w-full mb-4 rounded h-96">
            @endif

            {{-- Informasi Produk --}}
            <h3 class="mb-2 text-2xl font-bold">{{ $product->product_name }}</h3>
            <p class="mb-2 text-gray-600">Kategori: {{ $product->category->category_name }}</p>
            <p class="mb-2 font-semibold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="mb-4">{{ $product->description }}</p>

            <a href="{{ route('order.create', ['product' => $product->id]) }}"
                class="inline-block px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                Pesan Sekarang
            </a>


        </div>
    </div>
</x-app-layout>
