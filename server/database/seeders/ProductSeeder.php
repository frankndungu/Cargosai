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
            'slug' => 'handcrafted-maasai-earrings',
            'description' => 'Vibrant Beaded Design, Traditional African Patterns, Lightweight and Durable, Unique Cultural Artisan Piece',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660393/IMG_2056_edptam.jpg',
            'price' => 11.95,
            'vendor' => 'Maasai Crafts',
            'rating' => 0.0,
            'reviews' => 0,
            'stock' => 0,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        Product::create([
            'name' => 'Handcrafted Brass Necklace',
            'slug' => 'handcrafted-brass-necklace',
            'description' => 'Adjustable Length, Intricate Design, Durable and Stylish, Gold Finish',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1725290282/IMG_1841_pxxkgj.jpg',
            'price' => 9.99,
            'vendor' => 'Cultured Brass',
            'rating' => 0.0,
            'reviews' => 0,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        Product::create([
            'name' => 'Handcrafted Beaded Dining Table Mats',
            'slug' => 'handcrafted-beaded-dining-table-mats',
            'description' => 'Exquisite Artisan Craftsmanship, Vibrant Colors and Patterns, Durable and Easy to Clean, Adds a Unique Touch to Your Table Setting',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726658286/IMG_1875_1_pakgbb.jpg',
            'price' => 120.00,
            'vendor' => 'Beads by Judy',
            'rating' => 0.0,
            'reviews' => 0,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        Product::create([
            'name' => 'Wall Art',
            'slug' => 'wall-art',
            'description' => 'Unique African-Inspired Design, Vibrant Colors and Patterns, High-Quality Materials, Perfect for Adding a Cultural Touch to Your Space',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660769/IMG_1874_qpp9p1.jpg',
            'price' => 60.00,
            'vendor' => 'Shona Artists',
            'rating' => 0.0,
            'reviews' => 0,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]

        ]);

        Product::create([
            'name' => 'Handwoven African Basket',
            'slug' => 'handwoven-african-basket',
            'description' => 'Intricate Traditional Patterns, Durable and Versatile, Crafted by Skilled Artisans, Ideal for Decorative or Functional Use',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660981/IMG_1890_wqzy3f.jpg',
            'price' => 25.50,
            'vendor' => 'Kenya Weavers',
            'rating' => 5.0,
            'reviews' => 10,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        // Product 6
        Product::create([
            'name' => 'African Sculpture',
            'slug' => 'african-sculpture',
            'description' => 'Unique Traditional Design, Expertly Carved by Local Artisans, Made from High-Quality Materials, Adds Cultural Elegance to Any Space',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661077/IMG_1900_ejhcbh.jpg',
            'price' => 150.00,
            'vendor' => 'Sculptors United',
            'rating' => 4.0,
            'reviews' => 3,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        // Product 7
        Product::create([
            'name' => 'Traditional Maasai Shuka',
            'slug' => 'traditional-maasai-shuka',
            'description' => 'Vibrant Red and Black Checked Pattern, Handwoven with Authentic Maasai Techniques, Soft and Durable Fabric, Perfect for Cultural Events or Everyday Wear',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661254/IMG_1980_eyutwr.jpg',
            'price' => 50.00,
            'vendor' => 'Maasai Garments',
            'rating' => 5.0,
            'reviews' => 8,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        // Product 8
        Product::create([
            'name' => 'Beaded Maasai Bracelet',
            'slug' => 'beaded-maasai-bracelet',
            'description' => 'Intricately Handcrafted with Vibrant Beads, Traditional Maasai Patterns, Adjustable Fit, Durable and Stylish, Adds a Unique Cultural Touch to Any Outfit',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661345/IMG_1993_rnwytg.jpg',
            'price' => 15.00,
            'vendor' => 'Beads by Judy',
            'rating' => 5.0,
            'reviews' => 12,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        Product::create([
            'name' => 'Carved Wooden Stool',
            'slug' => 'carved-wooden-stool',
            'description' => 'Handcrafted with Intricate Designs, Solid and Durable Construction, Unique Artistic Touch, Perfect for Enhancing Any Space with a Rustic and Cultural Flair',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661457/IMG_2092_fjtgsj.jpg',
            'price' => 85.00,
            'vendor' => 'Woodworkers Guild',
            'rating' => 4.0,
            'reviews' => 6,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);

        Product::create([
            'name' => 'Decorative African Mask',
            'slug' => 'decorative-african-mask',
            'description' => 'Exquisitely Handcrafted with Traditional Designs, Vibrant Colors and Detailed Carvings, Ideal for Wall Art or Display, Adds a Distinctive Cultural Element to Your Home Décor',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661548/IMG_2098_yuipsj.jpg',
            'price' => 75.00,
            'vendor' => 'Mask Makers',
            'rating' => 3,
            'reviews' => 4.0,
            'stock' => 10,
            'thumbnails' => [
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg', 'alt' => 'Front view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg', 'alt' => 'Back view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg', 'alt' => 'Left view'],
                ['src' => 'https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png', 'alt' => 'Right view']
            ]
        ]);
    }
}
