<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Product Routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // All products
    Route::get('{id}', [ProductController::class, 'show']); // Individual product by ID
    Route::get('slug/{slug}', [ProductController::class, 'showBySlug']); // Individual product by slug
    Route::get('{id}/with-reviews', [ProductController::class, 'showWithReviews']); // Product with reviews
    Route::post('/', [ProductController::class, 'store']); // Store a new product
});

// Review Routes
Route::prefix('products/{productId}')->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index']); // Get reviews for a product
    Route::post('/reviews', [ReviewController::class, 'store']); // Store a new review
});

// User Route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
