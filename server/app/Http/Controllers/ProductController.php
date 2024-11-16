<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
    // Log the authentication status
    \Log::info('Auth Status:', [
        'is_authenticated' => Auth::check(),
        'user' => Auth::user(),
        'role' => Auth::user() ? Auth::user()->role : 'no role'
    ]);

    // Check if the logged-in user is an admin
    if (Auth::user()->role !== 'admin') {
        \Log::info('Authorization failed: User is not admin');
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Log the incoming request data
    \Log::info('Incoming Request Data:', [
        'all' => $request->all(),
        'files' => $request->allFiles(),
        'headers' => $request->headers->all()
    ]);

    try {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'main_image' => 'required|image|mimes:jpg,jpeg,png,gif,webp,avif',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'thumbnails' => 'nullable|array',
            'thumbnails.*' => 'image|mimes:jpg,jpeg,png,gif,webp,avif',
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'material' => 'nullable|string',
            'vendor_name' => 'required|string|max:255',
            'vendor_email' => 'required|email|max:255',
            'vendor_location' => 'required|string|max:255',
        ]);

        \Log::info('Validation passed:', $validatedData);

        // Handle main image upload
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $mainImagePath = $mainImage->store('products', 'public');
            \Log::info('Main Image uploaded:', ['path' => $mainImagePath]);
        } else {
            \Log::warning('No main image found in request');
        }

        // Handle thumbnails upload
        $thumbnails = [];
        if ($request->hasFile('thumbnails')) {
            foreach ($request->file('thumbnails') as $thumbnail) {
                $thumbnailPath = $thumbnail->store('thumbnails', 'public');
                $thumbnails[] = $thumbnailPath;
                \Log::info('Thumbnail uploaded:', ['path' => $thumbnailPath]);
            }
        }

        // Log the data about to be inserted
        \Log::info('Attempting to create product with data:', [
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'main_image' => $mainImagePath,
            'stock' => $validatedData['stock'],
            'thumbnails' => $thumbnails,
        ]);

        // Create product
        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'main_image' => $mainImagePath,
            'description' => $validatedData['description'] ?? '',
            'stock' => $validatedData['stock'],
            'thumbnails' => $thumbnails,
            'dimensions' => $validatedData['dimensions'] ?? '',
            'weight' => $validatedData['weight'] ?? null,
            'material' => $validatedData['material'] ?? '',
            'vendor_name' => $validatedData['vendor_name'],
            'vendor_email' => $validatedData['vendor_email'],
            'vendor_location' => $validatedData['vendor_location'],
        ]);

        \Log::info('Product created:', $product->toArray());

        return response()->json($product, 201);
    } catch (\Exception $e) {
        \Log::error('Error in product creation:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    
    public function update(Request $request, $id)
    {
        // Ensure only admins can update products
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Find the product
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'price' => 'numeric',
            'description' => 'nullable|string',
            'stock' => 'integer|min:0',
            'thumbnails' => 'nullable|array',
            'thumbnails.*.alt' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'material' => 'nullable|string',
            'vendor_name' => 'string|max:255',
            'vendor_email' => 'email|max:255',
            'vendor_location' => 'string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'thumbnails.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
        ]);

        // Handle file uploads for main image (if provided)
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('products/images', 'public');
            $validatedData['image_url'] = $mainImagePath; // Update the main image path
        }

        // Handle file uploads for thumbnails (if any)
        if ($request->has('thumbnails')) {
            $thumbnails = [];
            foreach ($request->file('thumbnails') as $thumbnail) {
                $thumbnails[] = [
                    'image' => $thumbnail->store('products/thumbnails', 'public'),
                    'alt' => $request->input('thumbnails')[$loop->index]['alt'] ?? null,
                ];
            }
            $validatedData['thumbnails'] = $thumbnails; // Update thumbnails
        }

        // Update the product with the validated data
        $product->update($validatedData);

        return response()->json($product, 200);
    }

    // Delete a product
    public function destroy($id)
    {
        // Ensure only admins can delete products
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Find the product
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Delete the main image if it exists
        if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
        }
    
        // Delete each thumbnail if it exists
        if ($product->thumbnails) {
            foreach ($product->thumbnails as $thumbnail) {
                if (Storage::disk('public')->exists($thumbnail)) {
                    Storage::disk('public')->delete($thumbnail);
                }
            }
        }
    
        // Delete the product
        $product->delete();
    
        return response()->json(['message' => 'Product and associated images deleted successfully'], 200);
    }
    
}
