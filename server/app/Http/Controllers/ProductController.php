<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Fetch 16 products per page with their average rating and review count from reviews
        $products = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate(16);

        $totalProducts = Product::count();

        // Format the average rating to 2 decimal points
        $products->getCollection()->transform(function ($product) {
            $product->reviews_avg_rating = number_format($product->reviews_avg_rating, 1);
            return $product;
        });

        // Return products as JSON, including the calculated average rating and reviews count
        return response()->json([
            'products' => $products,
            'total' => $totalProducts,
        ]);
    }

    public function show($id)
    {
        // Fetch a single product by ID including average review rating and total reviews
        $product = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->find($id);

        if (!$product) {
            // Return a 404 response if the product is not found
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Convert to array to add calculated fields
        $productData = $product->toArray();
        $productData['average_rating'] = round($product->reviews_avg_rating, 2); // Adjust precision as needed
        $productData['total_reviews'] = $product->reviews_count;

        return response()->json($productData);
    }

    public function showBySlug($slug)
    {
        // Fetch a single product by slug including average review rating and total reviews
        $product = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            // Return a 404 response if the product is not found
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Convert to array to add calculated fields
        $productData = $product->toArray();
        $productData['average_rating'] = round($product->reviews_avg_rating, 2); // Adjust precision as needed
        $productData['total_reviews'] = $product->reviews_count;

        return response()->json($productData);
    }

    public function showWithReviews($id)
    {
        // Fetch a single product by ID, including its reviews and average review rating
        $product = Product::with('reviews')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        // Convert to array to add calculated fields
        $productData = $product->toArray();
        $productData['average_rating'] = round($product->reviews_avg_rating, 2); // Adjust precision as needed
        $productData['total_reviews'] = $product->reviews_count;

        return response()->json($productData);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image_url' => 'required|string',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'thumbnails' => 'nullable|array',
            'thumbnails.*.src' => 'required_with:thumbnails|string',
            'thumbnails.*.alt' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'material' => 'nullable|string',
            'vendor_name' => 'required|string|max:255',
            'vendor_email' => 'required|email|max:255',
            'vendor_location' => 'required|string|max:255',
        ]);

        // Create a new product using the validated data
        $product = Product::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['name'], // Sluggable trait will generate this automatically
            'price' => $validatedData['price'],
            'image_url' => $validatedData['image_url'],
            'description' => $validatedData['description'] ?? '',
            'stock' => $validatedData['stock'],
            'thumbnails' => $validatedData['thumbnails'] ?? [],
            'dimensions' => $validatedData['dimensions'] ?? '',
            'weight' => $validatedData['weight'] ?? null,
            'material' => $validatedData['material'] ?? '',
            'vendor_name' => $validatedData['vendor_name'],
            'vendor_email' => $validatedData['vendor_email'],
            'vendor_location' => $validatedData['vendor_location'],
        ]);

        // Return the created product as JSON
        return response()->json($product, 201);
    }
}
