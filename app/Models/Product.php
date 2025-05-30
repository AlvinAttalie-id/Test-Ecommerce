<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'product';

    protected $fillable = [
        'product_name',
        'image_product',
        'gallery_product',
        'price',
        'qty',
        'description',
        'category_id',
    ];

    //Relationships
    protected $casts = [
        'gallery_product' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
