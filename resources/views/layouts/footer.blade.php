<div class="fixed inset-x-0 bottom-0 z-50 pointer-events-none sm:hidden">
    <div
        class="flex items-center justify-between px-4 py-2 bg-white border-t border-gray-200 shadow-lg pointer-events-auto rounded-t-2xl">
        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center flex-1 py-2 text-xs {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
            </svg>
            Home
        </a>
        <a href="{{ route('products.index') }}"
            class="flex flex-col items-center flex-1 py-2 text-xs {{ request()->routeIs('products.index') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 13h16v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4z" />
            </svg>
            Produk
        </a>
        <a href="{{ route('order.cart') }}"
            class="flex flex-col items-center flex-1 py-2 text-xs {{ request()->routeIs('order.cart') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m13-9l2 9m-5-4h.01M9 17h.01" />
            </svg>
            Cart
        </a>
        <a href="{{ route('transactions.history') }}"
            class="flex flex-col items-center flex-1 py-2 text-xs {{ request()->routeIs('transactions.history') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-4a1 1 0 011-1h3m0 0V7a4 4 0 10-8 0m8 5h1a3 3 0 013 3v4" />
            </svg>
            Riwayat
        </a>
    </div>
</div>
