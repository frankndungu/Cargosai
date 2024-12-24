<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_method', 'reference', 'status', 'amount'];

    protected $casts = [
        'amount' => 'decimal:2', // Ensure amounts are always in decimal format
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

