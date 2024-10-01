<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'image_url',
        'stock',
        'thumbnails',
        'rating',
        'description',
        'dimensions',
        'weight',
        'material',
        'vendor_name',
        'vendor_email',
        'vendor_location',
    ];

    /**
     * Relationship: A product can have many reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    protected $casts = [
        'thumbnails' => 'array', // Cast thumbnails as an array
    ];
}
