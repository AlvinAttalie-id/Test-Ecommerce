<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(['category_name' => 'iPhone']);
        Category::firstOrCreate(['category_name' => 'Samsung']);
    }
}
