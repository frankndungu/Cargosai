<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Session\Middleware\StartSession; // Import the StartSession middleware
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Import the Log facade

// Apply the rate limiting and session middleware to all routes in this file
Route::middleware([RateLimitMiddleware::class, StartSession::class])->group(function () {

    // Authentication Routes
    Route::post('/register', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
    });

    Route::post('/login', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
    });

    Route::post('/logout', function (Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    })->middleware('auth:sanctum');

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

    // User Route (requires authentication)
    Route::get('/user', function (Request $request) {
        try {
            // Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'error' => 'Unauthenticated.',
                    'message' => 'Please log in to access user information.'
                ], 401); // 401 Unauthorized
            }

            return response()->json($request->user(), 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving user info: ' . $e->getMessage());

            return response()->json([
                'error' => 'Unable to retrieve user information.',
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    })->middleware('auth:sanctum');
});
