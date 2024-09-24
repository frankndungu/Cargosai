<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// product Routes
Route::get('/products', [ProductController::class, 'index']); // all products
Route::get('products/{id}', [ProductController::class, 'show']); // route individual product by ID
Route::get('products/slug/{slug}', [ProductController::class, 'showBySlug']); // route individual product by slug for product overview
Route::post('products', [ProductController::class, 'store']);

// review Routes
Route::get('products/{productId}/reviews', [ReviewController::class, 'index']);
Route::post('reviews', [ReviewController::class, 'store']); // Make sure this is defined

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
