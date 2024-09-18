<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        //fetch all products from database
        $products = Product::all();
        //return products as JSON
        return response()->json($products);
    }
}
