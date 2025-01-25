<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'guest_id','status', 'total_price', 'shipping_fee', 'payment_status', 'reference', 'shipping_address_id', 'billing_address_id', 'guest_name', 'guest_email', 'guest_phone'];

    // Cast total_price to float
    protected $casts = [
        'total_price' => 'float',
        'shipping_fee' => 'float',
        'guest_id' => 'string', // Ensure UUID is treated as a string
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(BillingAddress::class);
    }

}
