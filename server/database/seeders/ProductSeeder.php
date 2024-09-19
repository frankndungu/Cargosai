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
            'name' => 'Handcrafted Maasai Earrings',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660393/IMG_2056_edptam.jpg',
            'price' => 11.95,
            'vendor' => 'Maasai Crafts',
            'rating' => 0,
            'reviews' => 0
        ]);

        Product::create([
            'name' => 'Handcrafted Brass Necklace',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1725290282/IMG_1841_pxxkgj.jpg',
            'price' => 9.99,
            'vendor' => 'Cultured Brass',
            'rating' => 0,
            'reviews' => 0
        ]);

        Product::create([
            'name' => 'Handcrafted Beaded Dining Table Mats',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726658286/IMG_1875_1_pakgbb.jpg',
            'price' => 120.00,
            'vendor' => 'Beads by Judy',
            'rating' => 0,
            'reviews' => 0
        ]);

        Product::create([
            'name' => 'Wall Art',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660769/IMG_1874_qpp9p1.jpg',
            'price' => 60.00,
            'vendor' => 'Shona Artists',
            'rating' => 0,
            'reviews' => 0
        ]);
    }
}
