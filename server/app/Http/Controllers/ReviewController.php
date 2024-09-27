<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    // Get all reviews from the database
    public function indexAll()
    {
        $reviews = Review::all(); // Fetch all reviews
        return response()->json($reviews);
    }

    // Get reviews for a specific product
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)->get();
        return response()->json($reviews);
    }

    // Store a new review
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'reviewer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Create a new review
        $review = Review::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'review' => $review,
        ], 201);
    }

    // Update an existing review
    public function update(Request $request, $reviewId)
    {
        $validated = $request->validate([
            'reviewer_name' => 'sometimes|required|string|max:255',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $review = Review::findOrFail($reviewId);
        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully.',
            'review' => $review,
        ]);
    }

    // Delete a review
    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.',
        ], 204);
    }
}
