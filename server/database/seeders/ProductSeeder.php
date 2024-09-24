<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Handcrafted Maasai Earrings',
                'slug' => 'handcrafted-maasai-earrings',
                'description' => 'Vibrant Beaded Design, Traditional African Patterns',
                'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660393/IMG_2056_edptam.jpg',
                'price' => 11.95,
                'vendor_name' => 'Maasai Crafts',
                'vendor_email' => 'info@maasaicrafts.com',
                'vendor_location' => 'Nairobi, Kenya',
                'rating' => 0.0,
                'reviews' => 0,
                'stock' => 0,
                'dimensions' => 'N/A',
                'weight' => 0.02,
                'material' => 'Beads',
                'thumbnails' => [
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
                ],
            ],
            [
                'name' => 'Handcrafted Brass Necklace',
                'slug' => 'handcrafted-brass-necklace',
                'description' => 'Adjustable Length, Durable and Stylish, Gold Finish',
                'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1725290282/IMG_1841_pxxkgj.jpg',
                'price' => 9.99,
                'vendor_name' => 'Cultured Brass',
                'vendor_email' => 'info@culturedbrass.com',
                'vendor_location' => 'Mombasa, Kenya',
                'rating' => 0.0,
                'reviews' => 0,
                'stock' => 10,
                'dimensions' => '50cm x 1cm',
                'weight' => 0.1,
                'material' => 'Brass',
                'thumbnails' => [
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
                ],
            ],
            [
                'name' => 'Handcrafted Beaded Dining Table Mats',
                'slug' => 'handcrafted-beaded-dining-table-mats',
                'description' => 'Vibrant Colors and Patterns, Durable and Easy to Clean',
                'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726658286/IMG_1875_1_pakgbb.jpg',
                'price' => 120.00,
                'vendor_name' => 'Beads by Judy',
                'vendor_email' => 'info@beadsbyjudy.com',
                'vendor_location' => 'Nairobi, Kenya',
                'rating' => 0.0,
                'reviews' => 0,
                'stock' => 10,
                'dimensions' => '40cm x 30cm',
                'weight' => 0.3,
                'material' => 'Cotton and Beads',
                'thumbnails' => [
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
                ],
            ],
            [
                'name' => 'Wall Art',
                'slug' => 'wall-art',
                'description' => 'Unique African-Inspired Design, High-Quality Materials',
                'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660769/IMG_1874_qpp9p1.jpg',
                'price' => 60.00,
                'vendor_name' => 'Shona Artists',
                'vendor_email' => 'info@shonaartists.com',
                'vendor_location' => 'Harare, Zimbabwe',
                'rating' => 0.0,
                'reviews' => 0,
                'stock' => 10,
                'dimensions' => '60cm x 90cm',
                'weight' => 1.5,
                'material' => 'Wood and Paint',
                'thumbnails' => [
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
                ],
            ],
            [
                'name' => 'Handwoven African Basket',
                'slug' => 'handwoven-african-basket',
                'description' => 'Intricate Traditional Patterns, Ideal for Decorative or Functional Use',
                'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660981/IMG_1890_wqzy3f.jpg',
                'price' => 25.50,
                'vendor_name' => 'Kenya Weavers',
                'vendor_email' => 'info@kenyaweavers.com',
                'vendor_location' => 'Nairobi, Kenya',
                'rating' => 5.0,
                'reviews' => 10,
                'stock' => 10,
                'dimensions' => '30cm diameter',
                'weight' => 0.4,
                'material' => 'Natural Fibers',
                'thumbnails' => [
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
                ],
            ],
            [
                'name' => 'African Sculpture',
                'slug' => 'african-sculpture',
                'description' => 'Unique Traditional Design, Made from High-Quality Materials',
                'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661077/IMG_1900_ejhcbh.jpg',
                'price' => 80.00,
                'vendor_name' => 'Zanzibar Artists',
                'vendor_email' => 'info@zanzibarartists.com',
                'vendor_location' => 'Zanzibar, Tanzania',
                'rating' => 0.0,
                'reviews' => 0,
                'stock' => 5,
                'dimensions' => '45cm x 25cm',
                'weight' => 2.0,
                'material' => 'Wood',
                'thumbnails' => [
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                    ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
                ],
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
