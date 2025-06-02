<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $iphone = Category::where('category_name', 'iPhone')->first();
        $samsung = Category::where('category_name', 'Samsung')->first();

        // Main product images
        $mainPhotos = ['P1.jpg', 'P2.jpg', 'P3.jpg', 'P4.jpg', 'P5.jpg'];

        // Gallery image filenames
        $iphoneGallery = ['i1.jpg', 'i2.jpg', 'i3.jpg', 'i4.jpg', 'i5.jpg', 'i6.jpg', 'i7.jpg', 'i8.jpg', 'i9.jpg', 'i10.jpg'];
        $samsungGallery = ['s1.jpg', 's2.jpg', 's3.jpg', 's4.jpg', 's5.jpg', 's6.jpg', 's7.jpg', 's8.jpg', 's9.jpg', 's10.jpg'];

        foreach (range(1, 50) as $i) {
            Product::create([
                'product_name' => "iPhone Product $i",
                'image_product' => 'products/' . Arr::random($mainPhotos),
                'gallery_product' => array_map(fn($img) => 'products/gallery/' . $img, Arr::random($iphoneGallery, 5)),
                'price' => rand(10000000, 20000000),
                'qty' => rand(1, 20),
                'description' => "Deskripsi produk iPhone $i",
                'category_id' => $iphone->id,
            ]);
        }

        foreach (range(1, 50) as $i) {
            Product::create([
                'product_name' => "Samsung Product $i",
                'image_product' => 'products/' . Arr::random($mainPhotos),
                'gallery_product' => array_map(fn($img) => 'products/gallery/' . $img, Arr::random($samsungGallery, 5)),
                'price' => rand(5000000, 15000000),
                'qty' => rand(1, 20),
                'description' => "Deskripsi produk Samsung $i",
                'category_id' => $samsung->id,
            ]);
        }
    }
}
