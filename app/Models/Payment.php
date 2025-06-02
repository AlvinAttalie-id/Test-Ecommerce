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

    public function getPaymentStatusBadgeClassAttribute()
    {
        return match ($this->payment_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-blue-100 text-blue-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }


    //Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
