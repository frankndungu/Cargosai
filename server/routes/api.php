<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\BillingAddressController;
use App\Http\Controllers\ShippingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

// Apply rate limiting and session middleware to all routes in this file
Route::middleware([RateLimitMiddleware::class])->group(function () {

    // Test email route (static route)
    Route::post('/send-test-email', function (Illuminate\Http\Request $request) {
        $to = $request->input('email');
    
        if (!$to) {
            return response()->json(['error' => 'Recipient email is required'], 400);
        }
    
        try {
            Mail::to($to)->send(new TestEmail());
            return response()->json(['success' => 'Test email sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
    
    // Auth Routes (static routes first)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/password/email', [AuthController::class, 'sendResetLink']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/email/resend', [AuthController::class, 'resendEmailVerification']);
    Route::get('/email/verify/{token}', [AuthController::class, 'confirmEmail']);

    // Admin Routes
    Route::post('/admin/login', [AdminController::class, 'login']);
    
    Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
        // Static admin routes first
        Route::get('/users/change', [UserController::class, 'getPercentageChangeOfNewUsers']);
        Route::post('/create', [AdminController::class, 'createAdminUser']);
        Route::put('/users/{id}/role', [AdminController::class, 'updateUserRole']);
        Route::get('/admins', [AdminController::class, 'listAdmins']);
        Route::get('/users/new', [UserController::class, 'getNewUsers']);
        Route::get('/users', [UserController::class, 'index']);
        // Parameter-based admin routes last
        Route::get('/users/{id}', [AdminController::class, 'showUser']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    });
    
    // Cart Routes (static routes first, then parameter routes)
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::get('/cart/total-items', [CartController::class, 'getTotalItems']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::delete('/cart', [CartController::class, 'deleteCart']);
    Route::put('/cart/item/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('/cart/item/{cartItem}', [CartController::class, 'removeItem']);

    // Product Routes
    Route::prefix('products')->group(function () {
        // Static routes first
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store'])->middleware('auth:sanctum');
        // Specific parameter routes
        Route::get('/slug/{slug}', [ProductController::class, 'showBySlug']);
        Route::get('/{id}/with-reviews', [ProductController::class, 'showWithReviews']);
        // Generic parameter routes last
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->middleware('auth:sanctum');
    });

    // Review Routes
    Route::prefix('reviews')->group(function () {
        // Static routes first
        Route::get('/', [ReviewController::class, 'indexAll']);
        // Specific parameter routes
        Route::get('/products/{productId}/average', [ReviewController::class, 'averageRating']);
        Route::get('/products/{productId}', [ReviewController::class, 'index']);
        Route::post('/products/{productId}', [ReviewController::class, 'store']);
        // Generic parameter routes last
        Route::put('/{reviewId}', [ReviewController::class, 'update']);
        Route::delete('/{reviewId}', [ReviewController::class, 'destroy']);
    });

    // Contact and Subscription Routes (static routes)
    Route::post('/contact', [ContactController::class, 'store']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);

    // Paystack Routes
    Route::post('/paystack/initialize', [PaystackController::class, 'initializePayment']);
    Route::get('/paystack/callback', [PaystackController::class, 'handleCallback'])->name('paystack.callback');

    // Payment Routes
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/revenue', [PaymentController::class, 'getRevenueSummary']);
    Route::get('/payments/monthly-sales/previous', [PaymentController::class, 'getPreviousYearMonthlySales']);
    Route::get('/payments/daily-sales', [PaymentController::class, 'getDailySalesData']);
    Route::get('/payments/monthly-sales', [PaymentController::class, 'getMonthlySalesData']);
    Route::get('/payments/revenue-change', [PaymentController::class, 'getRevenueChange']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);

    // Shipping Routes
    Route::post('/shipping-rates', [ShippingController::class, 'calculateRates']);

    // Order Route
    Route::post('/orders', [OrderController::class, 'create']);

    // Authenticated User Routes
    Route::middleware('auth:sanctum')->group(function () {
        // User Profile Routes
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        
        // User Management Routes
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        
        // Phone Number Routes
        Route::post('/users/{id}/phonenumber', [UserController::class, 'createPhoneNumber']);
        Route::put('/users/{id}/phonenumber', [UserController::class, 'updatePhoneNumber']);
        Route::delete('/users/{id}/phonenumber', [UserController::class, 'deletePhoneNumber']);

        // Shipping Address Routes
        Route::prefix('shipping-address')->group(function () {
            Route::post('/create', [ShippingAddressController::class, 'store']);
            Route::get('/user/{userId}', [ShippingAddressController::class, 'index']);
            Route::get('/{id}', [ShippingAddressController::class, 'show']);
            Route::put('/update/{id}', [ShippingAddressController::class, 'update']);
        });

        // Billing Address Routes
        Route::prefix('billing-address')->group(function () {
            Route::post('/create', [BillingAddressController::class, 'store']);
            Route::get('/user/{userId}', [BillingAddressController::class, 'index']);
            Route::get('/{id}', [BillingAddressController::class, 'show']);
            Route::put('/update/{id}', [BillingAddressController::class, 'update']);
        });

        // Orders Routes
        Route::prefix('orders')->group(function () {
            // Static routes first
            Route::get('/', [OrderController::class, 'index']);
            // Parameter routes last
            Route::get('/total', [OrderController::class, 'getTotalOrders']);
            Route::get('/completed-count', [OrderController::class, 'getCompletedOrdersCount']);
            Route::get('/recent', [OrderController::class, 'getRecentSales']);
            Route::get('/change', [OrderController::class, 'getOrderChange']);
            Route::get('/completed-change', [OrderController::class, 'getCompletedOrdersChange']);
            Route::get('/{order}', [OrderController::class, 'show']);
            Route::get('/{order}/details', [OrderDetailsController::class, 'show']);
            Route::put('/{order}/status', [OrderController::class, 'updateStatus']);
            Route::delete('/{order}', [OrderController::class, 'destroy']);
        });
    });
});