<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'reviewer_name' => 'Alice',  // Handcrafted Maasai Earrings
                'rating' => 5,
                'title' => 'Beautiful and authentic',
                'content' => 'These earrings are absolutely stunning! The craftsmanship is top-notch, and I love the vibrant colors. They add such a unique touch to any outfit. Highly recommend!',
                'product_id' => 1
            ],
            [
                'reviewer_name' => 'James',  // Handcrafted Maasai Earrings
                'rating' => 4,
                'title' => 'Great quality but slightly heavy',
                'content' => 'The earrings are beautiful and exactly as pictured. The only downside for me is that they are a bit heavier than expected. Still, they are great for special occasions!',
                'product_id' => 1
            ],
            [
                'reviewer_name' => 'Sophia',  // Handcrafted Maasai Earrings
                'rating' => 5,
                'title' => 'Amazing craftsmanship',
                'content' => 'I absolutely love these earrings! The detailing is amazing, and they feel like a piece of art. I\'ve already gotten so many compliments on them.',
                'product_id' => 1
            ],
            [
                'product_id' => 1, // Handcrafted Maasai Earrings
                'reviewer_name' => 'Jenny Graham',
                'rating' => 5,
                'title' => 'Beautifully Handcrafted and Culturally Rich',
                'content' => 'I recently purchased this Maasai beaded necklace from Maasai Market Online, and I couldn\'t be happier with my purchase! The craftsmanship is absolutely stunning.',
            ],
            [
                'product_id' => 2, // Handcrafted Brass Necklace
                'reviewer_name' => 'Michael Smith',
                'rating' => 4,
                'title' => 'Stylish and Durable',
                'content' => 'The brass necklace I ordered is both stylish and well-made. I highly recommend it!',
            ],
            [
                'product_id' => 3, // Handcrafted Beaded Dining Table Mats
                'reviewer_name' => 'Alice Johnson',
                'rating' => 3,
                'title' => 'Decent Quality',
                'content' => 'The dining table mats are nice, but I expected more vibrant colors.',
            ],
            [
                'product_id' => 4, // Wall Art
                'reviewer_name' => 'Samuel Lee',
                'rating' => 5,
                'title' => 'Stunning Art Piece!',
                'content' => 'This wall art has transformed my living space! Absolutely love it!',
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
