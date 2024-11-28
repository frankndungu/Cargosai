<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $cart = $this->getOrCreateCart($request);

        return response()->json([
            'cart' => $cart,
            'items' => $cart->items()->with('product')->get(),
        ]);
    }

    public function addItem(Request $request)
    {
        try {
            // Validate incoming request
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',  // Ensure the product exists
                'quantity' => 'required|integer|min:1',  // Ensure quantity is valid
            ]);
    
            // Get or create the cart
            $cart = $this->getOrCreateCart($request);
    
            // Check if the product already exists in the cart
            $item = $cart->items()->where('product_id', $validated['product_id'])->first();
    
            if ($item) {
                // Update the quantity if the product already exists in the cart
                $item->quantity += $validated['quantity'];
                $item->save();
    
                return response()->json([
                    'message' => 'Item quantity updated in cart',
                    'item' => $item,
                ]);
            } else {
                // Get the price from the products table
                $product = Product::find($validated['product_id']);
                if (!$product) {
                    return response()->json(['error' => 'Product not found'], 404);
                }
    
                // Add the new item to the cart
                $newItem = $cart->items()->create([
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,  // Use the product's price
                ]);
    
                return response()->json([
                    'message' => 'Item added to cart',
                    'item' => $newItem,
                ]);
            }
        } catch (\Exception $e) {
            // Catch any errors and return them in the response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Item updated', 'item' => $cartItem]);
    }

    public function removeItem(CartItem $cartItem)
    {
        $cartItem->delete();

        return response()->json(['message' => 'Item removed']);
    }

    /**
     * Get the total items in the cart.
     */
    public function getTotalItems(Request $request)
    {
        $cart = $this->getOrCreateCart($request);

        $totalItems = $cart->items()->sum('quantity');

        return response()->json(['total_items' => $totalItems]);
    }

    /**
     * Delete the cart and all its items.
     */
    public function deleteCart(Request $request)
    {
        $cart = $this->getOrCreateCart($request);

        $cart->items()->delete(); // Delete all cart items
        $cart->delete(); // Delete the cart itself

        return response()->json(['message' => 'Cart deleted successfully']);
    }
    // Helper method to get or create a cart for the given request.
    private function getOrCreateCart(Request $request)
    {
        // 1. For authenticated users, find or create a cart associated with their user ID
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        }

        // 2. For guest users, use a guest token from the cookies or generate a new one
        $guestToken = $request->cookie('guest_token') ?? Str::uuid();

        // 3. Find or create a cart using the guest token
        $cart = Cart::firstOrCreate(['guest_token' => $guestToken]);

        // 4. If a new guest token was generated, queue it in the cookies for persistence
        if (!$request->cookie('guest_token')) {
            cookie()->queue('guest_token', $guestToken, 60 * 24 * 30); // 30-day cookie
        }

        // 5. Return the identified or created cart
        return $cart;
    }

}
