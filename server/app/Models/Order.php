<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'total_price'];

    // Cast total_price to float
    protected $casts = [
        'total_price' => 'float',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Optional relationship with Payment model
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
