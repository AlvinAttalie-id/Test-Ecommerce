<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailOrder extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'detail_order';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    //Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
