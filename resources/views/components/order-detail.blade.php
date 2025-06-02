<x-filament::card>
    <x-filament::grid :columns="2" class="gap-4 text-sm text-gray-700 dark:text-gray-300">
        <div>
            <span class="font-semibold text-gray-900 dark:text-gray-100">Nama User</span>
            <p>{{ $order->user->name }}</p>
        </div>

        <div>
            <span class="font-semibold text-gray-900 dark:text-gray-100">Alamat</span>
            <p>{{ $order->shipping_address }}</p>
        </div>

        <div>
            <span class="font-semibold text-gray-900 dark:text-gray-100">Total Harga</span>
            <p>Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>

        <div>
            <span class="font-semibold text-gray-900 dark:text-gray-100">Tanggal Order</span>
            <p>{{ $order->order_date }}</p>
        </div>

        <div>
            <span class="font-semibold text-gray-900 dark:text-gray-100">Metode Pembayaran</span>
            <p class="capitalize">{{ $order->payment->payment_method }}</p>
        </div>

        <div>
            <span class="font-semibold text-gray-900 dark:text-gray-100">Status Pembayaran</span>
            <x-filament::badge
                color="{{ match ($order->payment->payment_status) {
                    'paid' => 'success',
                    'pending' => 'warning',
                    'failed' => 'danger',
                    default => 'gray',
                } }}">
                {{ ucfirst($order->payment->payment_status) }}
            </x-filament::badge>
        </div>

        <div>
            <span class="font-semibold text-gray-900 dark:text-gray-100">Status Order</span>
            <x-filament::badge
                color="{{ match ($order->status) {
                    'completed' => 'success',
                    'shipped' => 'info',
                    'paid', 'pending' => 'warning',
                    'failed' => 'danger',
                    default => 'gray',
                } }}">
                {{ ucfirst($order->status) }}
            </x-filament::badge>
        </div>
    </x-filament::grid>

    <div class="mt-6">
        <span class="font-semibold text-gray-900 dark:text-gray-100">Produk</span>
        <div class="mt-1 text-sm text-gray-700 dark:text-gray-300">
            {!! $produkList !!}
        </div>
    </div>
</x-filament::card>
