<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'guest_id', 'address1', 'country', 'state', 'city', 'postal_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
