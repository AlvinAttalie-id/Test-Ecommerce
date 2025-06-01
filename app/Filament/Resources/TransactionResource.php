<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;

class TransactionResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Daftar Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
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
                TextColumn::make('status')->label('Status Order')->badge(),
            ])
            ->filters([
                Filter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->form([
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->placeholder('Pilih status'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['status'])) {
                            $query->whereHas('payment', function ($q) use ($data) {
                                $q->where('payment_status', $data['status']);
                            });
                        }
                        return $query;
                    }),
            ])
            ->defaultSort('order_date', 'desc')
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->with(['user', 'details.product', 'payment'])
            );
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
