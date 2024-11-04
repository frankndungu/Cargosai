<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController; // Import AdminController
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\BillingAddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RateLimitMiddleware;
use App\Http\Middleware\AdminMiddleware; // Import AdminMiddleware
use Illuminate\Session\Middleware\StartSession;

// Apply rate limiting and session middleware to all routes in this file
Route::middleware([RateLimitMiddleware::class, StartSession::class])->group(function () {

    // Auth Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/admin/login', [AuthController::class, 'adminLogin']); // Admin login route
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    //Admin route
    Route::post('/admin/login', [AdminController::class, 'login']);
    
    Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']); // Get all users
        Route::get('/users/{id}', [UserController::class, 'show']); // Get individual user by ID
        Route::put('/users/{id}', [UserController::class, 'update']); // Update user
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user
    });

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('{id}', [ProductController::class, 'show']);
        Route::get('slug/{slug}', [ProductController::class, 'showBySlug']);
        Route::get('{id}/with-reviews', [ProductController::class, 'showWithReviews']);
        Route::post('/', [ProductController::class, 'store']);
    });

    // Review Routes
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'indexAll']);
        Route::get('/products/{productId}', [ReviewController::class, 'index']);
        Route::get('/products/{productId}/average', [ReviewController::class, 'averageRating']);
        Route::post('/products/{productId}', [ReviewController::class, 'store']);
        Route::put('/{reviewId}', [ReviewController::class, 'update']);
        Route::delete('/{reviewId}', [ReviewController::class, 'destroy']);
    });

    // Cart Routes
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::put('/cart/update/{itemId}', [CartController::class, 'updateItem']);
    Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeItem']);

    // User Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/users/{id}', [UserController::class, 'show']); // Get individual user by ID
        Route::put('/users/{id}', [UserController::class, 'update']); // Update user
        Route::post('/users/{id}/phonenumber', [UserController::class, 'createPhoneNumber']); // Create phone number
        Route::put('/users/{id}/phonenumber', [UserController::class, 'updatePhoneNumber']); // Update phone number
        Route::delete('/users/{id}/phonenumber', [UserController::class, 'deletePhoneNumber']); // Delete phone number
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::post('/users/{id}/phonenumber', [UserController::class, 'createPhoneNumber']);
        Route::put('/users/{id}/phonenumber', [UserController::class, 'updatePhoneNumber']);
        Route::delete('/users/{id}/phonenumber', [UserController::class, 'deletePhoneNumber']);

        // Shipping Address Routes
        Route::prefix('shipping-address')->group(function () {
            Route::get('/user/{userId}', [ShippingAddressController::class, 'index']);
            Route::post('/create', [ShippingAddressController::class, 'store']);
            Route::put('/update/{id}', [ShippingAddressController::class, 'update']);
            Route::get('/{id}', [ShippingAddressController::class, 'show']);
        });

        // Billing Address Routes
        Route::prefix('billing-address')->group(function () {
            Route::get('/user/{userId}', [BillingAddressController::class, 'index']);
            Route::post('/create', [BillingAddressController::class, 'store']);
            Route::put('/update/{id}', [BillingAddressController::class, 'update']);
            Route::get('/{id}', [BillingAddressController::class, 'show']);
        });

        // Orders Routes
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('{order}', [OrderController::class, 'show']);
            Route::get('{order}/details', [OrderDetailsController::class, 'show']);
            Route::post('/', [OrderController::class, 'create']);
            Route::put('{order}/status', [OrderController::class, 'updateStatus']);
            Route::delete('{order}', [OrderController::class, 'destroy']);
        });
    });

    // Admin Routes (Protected by AdminMiddleware)
    Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
        // Define your admin-specific routes here
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']); // Example admin route
        // Add more admin routes as needed
    });
});
