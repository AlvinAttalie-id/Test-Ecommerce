<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionSummary extends BaseWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();

        $totalPaid = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('payment_status', 'paid')
        )->sum('total_price');

        $yearPaid = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('payment_status', 'paid')
        )
            ->whereYear('order_date', $now->year)
            ->sum('total_price');

        $monthPaid = Order::whereHas(
            'payment',
            fn($q) =>
            $q->where('payment_status', 'paid')
        )
            ->whereYear('order_date', $now->year)
            ->whereMonth('order_date', $now->month)
            ->sum('total_price');

        return [
            Stat::make(
                'Total Dana',
                'Rp' . number_format($totalPaid, 0, ',', '.')
            )->description('Semua transaksi berhasil bayar')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make(
                'Dana Masuk Tahun Ini',
                'Rp' . number_format($yearPaid, 0, ',', '.')
            )->description('Transaksi lunas tahun berjalan')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make(
                'Dana Masuk Bulan Ini',
                'Rp' . number_format($monthPaid, 0, ',', '.')
            )->description('Transaksi lunas bulan ini')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('warning'),
        ];
    }

    protected static ?int $sort = -1;
}
