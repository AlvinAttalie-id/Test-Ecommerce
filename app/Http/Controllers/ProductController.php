<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(10);
        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }
}
