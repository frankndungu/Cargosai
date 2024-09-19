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

        Product::create([
            'name' => 'Handwoven African Basket',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660981/IMG_1890_wqzy3f.jpg',
            'price' => 25.50,
            'vendor' => 'Kenya Weavers',
            'rating' => 5,
            'reviews' => 10
        ]);

        // Product 6
        Product::create([
            'name' => 'African Sculpture',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661077/IMG_1900_ejhcbh.jpg',
            'price' => 150.00,
            'vendor' => 'Sculptors United',
            'rating' => 4,
            'reviews' => 3
        ]);

        // Product 7
        Product::create([
            'name' => 'Traditional Maasai Shuka',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661254/IMG_1980_eyutwr.jpg',
            'price' => 50.00,
            'vendor' => 'Maasai Garments',
            'rating' => 5,
            'reviews' => 8
        ]);

        // Product 8
        Product::create([
            'name' => 'Beaded Maasai Bracelet',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661345/IMG_1993_rnwytg.jpg',
            'price' => 15.00,
            'vendor' => 'Beads by Judy',
            'rating' => 5,
            'reviews' => 12
        ]);

        // Repeat the pattern to make up to 36 products.
        Product::create([
            'name' => 'Carved Wooden Stool',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661457/IMG_2092_fjtgsj.jpg',
            'price' => 85.00,
            'vendor' => 'Woodworkers Guild',
            'rating' => 4,
            'reviews' => 6
        ]);

        Product::create([
            'name' => 'Decorative African Mask',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661548/IMG_2098_yuipsj.jpg',
            'price' => 75.00,
            'vendor' => 'Mask Makers',
            'rating' => 3,
            'reviews' => 4
        ]);

        Product::create([
            'name' => 'Handwoven African Basket',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660981/IMG_1890_wqzy3f.jpg',
            'price' => 25.50,
            'vendor' => 'Kenya Weavers',
            'rating' => 5,
            'reviews' => 10
        ]);

        // Product 6
        Product::create([
            'name' => 'African Sculpture',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661077/IMG_1900_ejhcbh.jpg',
            'price' => 150.00,
            'vendor' => 'Sculptors United',
            'rating' => 4,
            'reviews' => 3
        ]);

        // Product 7
        Product::create([
            'name' => 'Traditional Maasai Shuka',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661254/IMG_1980_eyutwr.jpg',
            'price' => 50.00,
            'vendor' => 'Maasai Garments',
            'rating' => 5,
            'reviews' => 8
        ]);

        // Product 8
        Product::create([
            'name' => 'Beaded Maasai Bracelet',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661345/IMG_1993_rnwytg.jpg',
            'price' => 15.00,
            'vendor' => 'Beads by Judy',
            'rating' => 5,
            'reviews' => 12
        ]);

        // Add remaining products in a similar fashion

        // Repeat the pattern to make up to 36 products.
        Product::create([
            'name' => 'Carved Wooden Stool',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661457/IMG_2092_fjtgsj.jpg',
            'price' => 85.00,
            'vendor' => 'Woodworkers Guild',
            'rating' => 4,
            'reviews' => 6
        ]);

        Product::create([
            'name' => 'Decorative African Mask',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661548/IMG_2098_yuipsj.jpg',
            'price' => 75.00,
            'vendor' => 'Mask Makers',
            'rating' => 3,
            'reviews' => 4
        ]);

        Product::create([
            'name' => 'Handwoven African Basket',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660981/IMG_1890_wqzy3f.jpg',
            'price' => 25.50,
            'vendor' => 'Kenya Weavers',
            'rating' => 5,
            'reviews' => 10
        ]);

        // Product 6
        Product::create([
            'name' => 'African Sculpture',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661077/IMG_1900_ejhcbh.jpg',
            'price' => 150.00,
            'vendor' => 'Sculptors United',
            'rating' => 4,
            'reviews' => 3
        ]);

        // Product 7
        Product::create([
            'name' => 'Traditional Maasai Shuka',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661254/IMG_1980_eyutwr.jpg',
            'price' => 50.00,
            'vendor' => 'Maasai Garments',
            'rating' => 5,
            'reviews' => 8
        ]);

        // Product 8
        Product::create([
            'name' => 'Beaded Maasai Bracelet',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661345/IMG_1993_rnwytg.jpg',
            'price' => 15.00,
            'vendor' => 'Beads by Judy',
            'rating' => 5,
            'reviews' => 12
        ]);

        // Repeat the pattern to make up to 36 products.
        Product::create([
            'name' => 'Carved Wooden Stool',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661457/IMG_2092_fjtgsj.jpg',
            'price' => 85.00,
            'vendor' => 'Woodworkers Guild',
            'rating' => 4,
            'reviews' => 6
        ]);

        Product::create([
            'name' => 'Decorative African Mask',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661548/IMG_2098_yuipsj.jpg',
            'price' => 75.00,
            'vendor' => 'Mask Makers',
            'rating' => 3,
            'reviews' => 4
        ]);

        Product::create([
            'name' => 'Handwoven African Basket',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660981/IMG_1890_wqzy3f.jpg',
            'price' => 25.50,
            'vendor' => 'Kenya Weavers',
            'rating' => 5,
            'reviews' => 10
        ]);

        // Product 6
        Product::create([
            'name' => 'African Sculpture',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661077/IMG_1900_ejhcbh.jpg',
            'price' => 150.00,
            'vendor' => 'Sculptors United',
            'rating' => 4,
            'reviews' => 3
        ]);

        // Product 7
        Product::create([
            'name' => 'Traditional Maasai Shuka',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661254/IMG_1980_eyutwr.jpg',
            'price' => 50.00,
            'vendor' => 'Maasai Garments',
            'rating' => 5,
            'reviews' => 8
        ]);

        // Product 8
        Product::create([
            'name' => 'Beaded Maasai Bracelet',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661345/IMG_1993_rnwytg.jpg',
            'price' => 15.00,
            'vendor' => 'Beads by Judy',
            'rating' => 5,
            'reviews' => 12
        ]);

        // Repeat the pattern to make up to 36 products.
        Product::create([
            'name' => 'Carved Wooden Stool',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661457/IMG_2092_fjtgsj.jpg',
            'price' => 85.00,
            'vendor' => 'Woodworkers Guild',
            'rating' => 4,
            'reviews' => 6
        ]);

        Product::create([
            'name' => 'Decorative African Mask',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661548/IMG_2098_yuipsj.jpg',
            'price' => 75.00,
            'vendor' => 'Mask Makers',
            'rating' => 3,
            'reviews' => 4
        ]);

        Product::create([
            'name' => 'Handwoven African Basket',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726660981/IMG_1890_wqzy3f.jpg',
            'price' => 25.50,
            'vendor' => 'Kenya Weavers',
            'rating' => 5,
            'reviews' => 10
        ]);

        // Product 6
        Product::create([
            'name' => 'African Sculpture',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661077/IMG_1900_ejhcbh.jpg',
            'price' => 150.00,
            'vendor' => 'Sculptors United',
            'rating' => 4,
            'reviews' => 3
        ]);

        // Product 7
        Product::create([
            'name' => 'Traditional Maasai Shuka',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661254/IMG_1980_eyutwr.jpg',
            'price' => 50.00,
            'vendor' => 'Maasai Garments',
            'rating' => 5,
            'reviews' => 8
        ]);

        // Product 8
        Product::create([
            'name' => 'Beaded Maasai Bracelet',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661345/IMG_1993_rnwytg.jpg',
            'price' => 15.00,
            'vendor' => 'Beads by Judy',
            'rating' => 5,
            'reviews' => 12
        ]);

        // Add remaining products in a similar fashion

        // Repeat the pattern to make up to 36 products.
        Product::create([
            'name' => 'Carved Wooden Stool',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661457/IMG_2092_fjtgsj.jpg',
            'price' => 85.00,
            'vendor' => 'Woodworkers Guild',
            'rating' => 4,
            'reviews' => 6
        ]);

        Product::create([
            'name' => 'Decorative African Mask',
            'image_url' => 'https://res.cloudinary.com/kwishi/image/upload/v1726661548/IMG_2098_yuipsj.jpg',
            'price' => 75.00,
            'vendor' => 'Mask Makers',
            'rating' => 3,
            'reviews' => 4
        ]);
    }
}
