<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $modelLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'category_name')
                    ->searchable()
                    ->required(),

                Forms\Components\FileUpload::make('image_product')
                    ->label('Gambar Utama')
                    ->image()
                    ->directory('products')
                    ->disk('public') // simpan ke storage/app/public/products
                    ->required(),

                Forms\Components\FileUpload::make('gallery_product')
                    ->label('Galeri Produk')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->directory('products/gallery')
                    ->disk('public'),

                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('qty')
                    ->label('Stok')
                    ->numeric()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('image_product')
                    ->label('Gambar')
                    ->disk('public') // ambil dari storage/app/public
                    ->visibility('public'),

                Tables\Columns\TextColumn::make('category.category_name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Stok'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Filter Kategori')
                    ->relationship('category', 'category_name')
                    ->searchable(),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
