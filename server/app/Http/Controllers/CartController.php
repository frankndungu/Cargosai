<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Store a new cart item or update the quantity of an existing item in the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id', // Check if the product exists
            'quantity' => 'required|integer|min:1', // Validate the quantity
        ]);

        $user = $request->user(); // Get the authenticated user
        $sessionId = $request->session()->getId(); // Get the session ID for guest users

        // Fetch or create the cart (for logged-in users or guests)
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id ?? null, 'session_id' => $sessionId]
        );

        // Check if the product is already in the cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // If the item exists, update the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // If the item doesn't exist, create a new cart item
            $product = Product::findOrFail($request->product_id); // Get the product details
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price, // Store the product price
            ]);
        }

        return response()->json(['message' => 'Item added to cart']);
    }

    /**
     * Get all items in the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user(); // Get the authenticated user
        $sessionId = $request->session()->getId(); // Get the session ID for guest users

        // Fetch the cart for the logged-in user or guest
        $cart = Cart::with('items.product') // Eager load cart items and their associated product
            ->where('user_id', $user->id)
            ->orWhere('session_id', $sessionId)
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        return response()->json(['cart' => $cart]);
    }

    /**
     * Update the quantity of a cart item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The cart item ID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the quantity input
        $request->validate([
            'quantity' => 'required|integer|min:1', // Validate the quantity
        ]);

        $cartItem = CartItem::find($id); // Find the cart item by ID

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        // Update the quantity of the cart item
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cart item updated']);
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int  $id  The cart item ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cartItem = CartItem::find($id); // Find the cart item by ID

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        // Delete the cart item
        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed']);
    }
}
