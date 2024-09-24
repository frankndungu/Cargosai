<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'products';
    protected $fillable = ['name', 'slug', 'price', 'image_url',  'stock', 'thumbnails', 'vendor', 'rating', 'reviews', 'description', 'dimensions', 'weight', 'material'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $casts = [
        'thumbnails' => 'array', // Cast thumbnails as an array
    ];
}
