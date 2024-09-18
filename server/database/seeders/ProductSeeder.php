<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Handcrafted Beaded Neckpieces',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1723452565/6D70881A-9F58-4819-B478-61ACD4CA710E_ib7vty.jpg',
            'price' => 50.00,
            'vendor' => 'Maasai Crafts',
            'rating' => 5,
            'reviews' => 120
        ]);

        Product::create([
            'name' => 'Handcrafted Brass Necklace',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1725290282/IMG_1841_pxxkgj.jpg',
            'price' => 9.99,
            'vendor' => 'Cultured Brass',
            'rating' => 4,
            'reviews' => 80
        ]);

        Product::create([
            'name' => 'Handcrafted Beaded Dining Table Mats',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1723442262/IMG_1875_l717yl.jpg',
            'price' => 120.00,
            'vendor' => 'Beads by Judy',
            'rating' => 5,
            'reviews' => 95
        ]);

        Product::create([
            'name' => 'Wall Art',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1723447821/IMG_1874_o6xagp.jpg',
            'price' => 60.00,
            'vendor' => 'Shona Artists',
            'rating' => 4,
            'reviews' => 50
        ]);
    }
}
