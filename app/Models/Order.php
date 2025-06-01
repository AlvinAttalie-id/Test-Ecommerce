<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'order';

    protected $fillable = [
        'user_id',
        'order_date',
        'total_price',
        'status',
        'shipping_address',
    ];

    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
