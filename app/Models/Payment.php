<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'payment';

    protected $fillable = [
        'order_id',
        'payment_date',
        'payment_method',
        'payment_status',
    ];

    //Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
