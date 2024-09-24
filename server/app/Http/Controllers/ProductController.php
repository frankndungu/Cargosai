<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Fetch 16 products per page from the database
        $products = Product::paginate(16);
        $totalProducts = Product::count();

        // Return products as JSON
        return response()->json([
            'products' => $products,
            'total' => $totalProducts
        ]);
    }

    public function show($id) // Changed from $slug to $id
    {
        // Fetch a single product by ID
        $product = Product::find($id);

        if (!$product) {
            // Return a 404 response if the product is not found
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Return the product as JSON, including the slug
        return response()->json($product);
    }

    public function showBySlug($slug)
    {
        // Fetch a single product by slug
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            // Return a 404 response if the product is not found
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Return the product as JSON
        return response()->json($product);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image_url' => 'required|string',
            'rating' => 'nullable|integer',
            'reviews' => 'nullable|integer',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0', // Validate stock
            'thumbnails' => 'nullable|array', // Expect an array for thumbnails
            'thumbnails.*.src' => 'required_with:thumbnails|string', // Each thumbnail must have a 'src'
            'thumbnails.*.alt' => 'nullable|string', // Each thumbnail can have an 'alt' tag
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'material' => 'nullable|string',
            'vendor_name' => 'required|string|max:255',
            'vendor_email' => 'required|email|max:255',
            'vendor_location' => 'required|string|max:255',
        ]);

        // Create a new product using the validated data, including thumbnails
        $product = Product::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['name'], // Sluggable trait will generate this automatically
            'price' => $validatedData['price'],
            'image_url' => $validatedData['image_url'],
            'rating' => $validatedData['rating'] ?? 0, // Default rating to 0 if not provided
            'reviews' => $validatedData['reviews'] ?? 0, // Default reviews to 0 if not provided
            'description' => $validatedData['description'] ?? '', // Default description to empty string
            'stock' => $validatedData['stock'], // Store stock
            'thumbnails' => $validatedData['thumbnails'] ?? [], // Store the thumbnails as JSON, empty array if not provided
            'dimensions' => $validatedData['dimensions'] ?? '',
            'weight' => $validatedData['weight'] ?? null,
            'material' => $validatedData['material'] ?? '',
            'vendor_name' => $validatedData['vendor_name'], // Save vendor name
            'vendor_email' => $validatedData['vendor_email'], // Save vendor email
            'vendor_location' => $validatedData['vendor_location'], // Save vendor location
        ]);

        // Return the created product as JSON
        return response()->json($product, 201); // 201 status code means 'Created'
    }
}
