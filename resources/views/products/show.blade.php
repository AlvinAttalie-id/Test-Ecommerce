<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-slate-800">
            {{ $product->product_name }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-10 bg-gray-50">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 space-y-6 overflow-hidden bg-white shadow-xl rounded-3xl md:grid md:grid-cols-2 md:gap-10">
                {{-- Carousel --}}
                <div class="w-full max-w-md mx-auto sm:max-w-lg lg:max-w-xl">
                    @if ($product->gallery_product && count($product->gallery_product) > 0)
                        <div x-data="{ current: 0 }" class="relative">
                            <div class="overflow-hidden shadow-sm rounded-2xl h-96">
                                <template x-for="(image, index) in {{ json_encode($product->gallery_product) }}"
                                    :key="index">
                                    <div x-show="current === index" class="w-full h-full">
                                        <img :src="'/storage/' + image" alt=""
                                            class="object-cover w-full h-full transition duration-300 ease-in-out rounded-2xl">
                                    </div>
                                </template>
                            </div>

                            <button
                                @click="current = (current === 0) ? {{ count($product->gallery_product) - 1 }} : current - 1"
                                class="absolute left-0 p-2 text-white -translate-y-1/2 rounded-full top-1/2 bg-black/30 hover:bg-black/50">
                                ‹
                            </button>
                            <button
                                @click="current = (current === {{ count($product->gallery_product) - 1 }}) ? 0 : current + 1"
                                class="absolute right-0 p-2 text-white -translate-y-1/2 rounded-full top-1/2 bg-black/30 hover:bg-black/50">
                                ›
                            </button>

                            <div class="flex justify-center gap-3 mt-4">
                                <template x-for="(image, index) in {{ json_encode($product->gallery_product) }}"
                                    :key="index">
                                    <button @click="current = index"
                                        :class="{ 'ring-2 ring-indigo-500': current === index }"
                                        class="w-16 h-16 overflow-hidden transition border rounded-lg hover:opacity-80">
                                        <img :src="'/storage/' + image" class="object-cover w-full h-full">
                                    </button>
                                </template>
                            </div>
                        </div>
                    @else
                        <img src="{{ asset('storage/' . $product->image_product) }}" alt=""
                            class="object-cover w-full shadow h-96 rounded-2xl">
                    @endif
                </div>

                {{-- Informasi Produk --}}
                <div class="pb-6 space-y-4 sm:pb-8 lg:pb-10">
                    <h3 class="text-3xl font-extrabold text-slate-900">{{ $product->product_name }}</h3>

                    <p class="text-sm text-gray-500">
                        Kategori: <span class="font-medium text-gray-700">{{ $product->category->category_name }}</span>
                    </p>

                    <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <p class="text-sm text-gray-600">Sisa Stok:
                        <span class="font-semibold text-gray-800">{{ $product->qty }}</span>
                    </p>

                    <p class="leading-relaxed text-gray-700">{{ $product->description }}</p>

                    {{-- Aksi --}}
                    <div class="flex flex-col items-start gap-3 mt-4 sm:flex-row sm:mt-6 lg:mt-8">
                        <form action="{{ route('order.cart.add') }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <input type="number" name="quantity" value="1" min="1"
                                max="{{ $product->qty }}"
                                class="w-20 px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:border-green-500">

                            <button type="submit" title="Tambah ke Keranjang"
                                class="inline-flex items-center justify-center w-12 h-12 text-white transition bg-green-600 rounded-full shadow hover:bg-green-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7h13L17 13M7 13H5.4m1.6 0l1.5-7m8.4 7L17 13" />
                                </svg>
                            </button>
                        </form>

                        <a href="{{ route('order.create', ['product' => $product->id]) }}"
                            class="inline-flex items-center justify-center px-6 py-3 font-semibold text-white transition bg-indigo-600 shadow hover:bg-indigo-700 rounded-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c.552 0 1 .448 1 1v5m0 0c0 .552-.448 1-1 1s-1-.448-1-1v-5m1 0v-2m0 12a9 9 0 110-18 9 9 0 010 18z" />
                            </svg>
                            Pesan Sekarang
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
