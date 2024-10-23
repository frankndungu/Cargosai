<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\ShippingAddressController; // Add the ShippingAddressController
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

    // User Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user(); // Get the authenticated user
        });
        Route::get('/users', [UserController::class, 'index']); // Get all users
        Route::get('/users/{id}', [UserController::class, 'show']); // Get individual user by ID
        Route::put('/users/{id}', [UserController::class, 'update']); // Update user
        Route::post('/users/{id}/phonenumber', [UserController::class, 'createPhoneNumber']); // Create phone number
        Route::put('/users/{id}/phonenumber', [UserController::class, 'updatePhoneNumber']); // Update phone number
        Route::delete('/users/{id}/phonenumber', [UserController::class, 'deletePhoneNumber']); // Delete phone number

        // Shipping Address Routes
        Route::prefix('shipping-address')->group(function () {
            Route::get('/user/{userId}', [ShippingAddressController::class, 'index']); // Fetch all addresses for a user
            Route::post('/create', [ShippingAddressController::class, 'store']); // Store new shipping address
            Route::put('/update/{id}', [ShippingAddressController::class, 'update']); // Update an existing shipping address
            Route::get('/{id}', [ShippingAddressController::class, 'show']); // Get a specific shipping address by ID
        });

        // Orders Routes
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']); // Get all orders for the authenticated user
            Route::get('{order}', [OrderController::class, 'show']); // Get a specific order by ID
            Route::get('{order}/details', [OrderDetailsController::class, 'show']); // Get details of a specific order
            Route::post('/', [OrderController::class, 'create']); // Create a new order
            Route::put('{order}/status', [OrderController::class, 'updateStatus']); // Update order status
            Route::delete('{order}', [OrderController::class, 'destroy']); // Delete a specific order
        });
    });
});
