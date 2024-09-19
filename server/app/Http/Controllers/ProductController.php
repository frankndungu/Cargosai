<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        //fetch 16 products per page from the database
        $products = Product::paginate(16);
        $totalProducts = Product::count();

        //return products as JSON
        return response()->json([
            'products' => $products,
            'total' => $totalProducts
        ]);
    }
}
