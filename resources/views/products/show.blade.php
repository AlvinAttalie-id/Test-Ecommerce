<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            {{ $product->product_name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow rounded-2xl">
                {{-- Carousel --}}
                @if ($product->gallery_product && count($product->gallery_product) > 0)
                    <div x-data="{ current: 0 }" class="relative mb-6">
                        <div class="overflow-hidden rounded-xl h-96">
                            <template x-for="(image, index) in {{ json_encode($product->gallery_product) }}"
                                :key="index">
                                <div x-show="current === index" class="w-full h-full">
                                    <img :src="'/storage/' + image" alt=""
                                        class="object-cover w-full h-full transition duration-300 ease-in-out">
                                </div>
                            </template>
                        </div>

                        <button
                            @click="current = (current === 0) ? {{ count($product->gallery_product) - 1 }} : current - 1"
                            class="absolute left-0 px-3 py-2 text-white -translate-y-1/2 bg-black rounded-l top-1/2 bg-opacity-30 hover:bg-opacity-50">
                            ‹
                        </button>
                        <button
                            @click="current = (current === {{ count($product->gallery_product) - 1 }}) ? 0 : current + 1"
                            class="absolute right-0 px-3 py-2 text-white -translate-y-1/2 bg-black rounded-r top-1/2 bg-opacity-30 hover:bg-opacity-50">
                            ›
                        </button>

                        {{-- Thumbnails --}}
                        <div class="flex justify-center mt-4 space-x-3">
                            <template x-for="(image, index) in {{ json_encode($product->gallery_product) }}"
                                :key="index">
                                <button @click="current = index" :class="{ 'ring-2 ring-blue-500': current === index }"
                                    class="w-16 h-16 overflow-hidden rounded-md hover:opacity-75 focus:outline-none">
                                    <img :src="'/storage/' + image" class="object-cover w-full h-full rounded">
                                </button>
                            </template>
                        </div>
                    </div>
                @else
                    <img src="{{ asset('storage/' . $product->image_product) }}" alt=""
                        class="object-cover w-full mb-6 h-96 rounded-xl">
                @endif

                {{-- Informasi Produk --}}
                <div class="space-y-4">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $product->product_name }}</h3>
                    <p class="text-sm text-gray-500">Kategori: <span
                            class="font-medium">{{ $product->category->category_name }}</span></p>
                    <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="leading-relaxed text-gray-700">{{ $product->description }}</p>

                    <a href="{{ route('order.create', ['product' => $product->id]) }}"
                        class="inline-block px-6 py-3 mt-4 font-semibold text-white transition duration-200 bg-blue-600 rounded-lg shadow hover:bg-blue-700">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
