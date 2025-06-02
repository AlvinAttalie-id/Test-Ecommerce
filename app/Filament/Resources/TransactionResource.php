<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Product;
use Filament\Tables\Columns\SelectColumn;

class TransactionResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Daftar Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Sudah tidak dipakai, hanya untuk halaman edit
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nama User'),
                TextColumn::make('details.0.product.product_name')->label('Produk'),
                TextColumn::make('details.0.quantity')->label('Jumlah'),
                TextColumn::make('total_price')->label('Total Harga')->money('IDR'),
                TextColumn::make('order_date')->label('Tanggal Order')->dateTime(),
                TextColumn::make('shipping_address')->label('Alamat'),
                TextColumn::make('payment.payment_method')->label('Metode Pembayaran'),

                TextColumn::make('payment.payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                SelectColumn::make('status')
                    ->label('Status Order')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])

            ])
            ->filters([
                Filter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->form([
                        Select::make('payment_status_value')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->placeholder('Pilih status'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['payment_status_value'] ?? false) {
                            $query->whereHas('payment', function ($q) use ($data) {
                                $q->where('payment_status', $data['payment_status_value']);
                            });
                        }
                    }),

                Filter::make('status')
                    ->label('Status Order')
                    ->form([
                        Select::make('order_status_value')
                            ->label('Status Order')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'shipped' => 'Shipped',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->placeholder('Pilih status'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['order_status_value'] ?? false) {
                            $query->where('status', $data['order_status_value']);
                        }
                    }),
            ])

            ->defaultSort('order_date', 'desc')
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->with(['user', 'details.product', 'payment'])
            )
            ->actions([
                Tables\Actions\EditAction::make(), // opsional, tetap ditampilkan

                Action::make('confirm')
                    ->label('Konfirmasi')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(
                        fn($record) =>
                        $record->status === 'pending' &&
                            $record->payment?->payment_method === 'cod' &&
                            $record->payment?->payment_status === 'pending'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // Update status order dan payment
                        $record->update(['status' => 'paid']);
                        $record->payment->update(['payment_status' => 'paid']);

                        // Kurangi stok produk
                        foreach ($record->details as $detail) {
                            Product::where('id', $detail->product_id)
                                ->decrement('qty', $detail->quantity);
                        }

                        Notification::make()
                            ->title('Pesanan berhasil dikonfirmasi')
                            ->success()
                            ->send();
                    }),

                Action::make('view')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Transaksi')
                    ->modalContent(function ($record) {
                        $produkList = $record->details->map(function ($item) {
                            return "- {$item->product->product_name} x {$item->quantity}";
                        })->implode("<br>");

                        return view('components.order-detail', [
                            'order' => $record,
                            'produkList' => $produkList,
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
