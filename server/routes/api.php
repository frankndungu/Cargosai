<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Session\Middleware\StartSession; // Import the StartSession middleware

// Apply the rate limiting and session middleware to all routes in this file
Route::middleware([RateLimitMiddleware::class, StartSession::class])->group(function () {

    // Auth Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']); // All products
        Route::get('{id}', [ProductController::class, 'show']); // Individual product by ID
        Route::get('slug/{slug}', [ProductController::class, 'showBySlug']); // Individual product by slug
        Route::get('{id}/with-reviews', [ProductController::class, 'showWithReviews']); // Product with reviews
        Route::post('/', [ProductController::class, 'store']); // Store a new product
    });

    // Review Routes
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'indexAll']); // Get all reviews
        Route::get('/products/{productId}', [ReviewController::class, 'index']); // Get reviews for a specific product
        Route::get('/products/{productId}/average', [ReviewController::class, 'averageRating']); // Get average rating for a specific product
        Route::post('/products/{productId}', [ReviewController::class, 'store']); // Store a new review for a specific product
        Route::put('/{reviewId}', [ReviewController::class, 'update']); // Update an existing review
        Route::delete('/{reviewId}', [ReviewController::class, 'destroy']); // Delete a review
    });

    // Cart Routes
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::put('/cart/update/{itemId}', [CartController::class, 'updateItem']);
    Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeItem']);

    // User Route
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
});
