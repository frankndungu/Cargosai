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
