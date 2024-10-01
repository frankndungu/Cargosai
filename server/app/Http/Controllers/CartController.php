<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Ensure this is included
use Illuminate\Database\Eloquent\ModelNotFoundException; // Include this for error handling
use Exception; // For general exception handling

class CartController extends Controller
{
    // Get the cart for the authenticated user or a guest
    public function getCart(Request $request)
    {
        $cart = $this->getUserCart($request);
        return response()->json($cart->load('cartItems'));
    }

    // Add item to the cart
    public function addItem(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric', // Assuming price is passed in the request
            ]);

            // Get the user's cart
            $cart = $this->getUserCart($request);

            // Find the existing cart item
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                // If the cart item exists, update the quantity
                $cartItem->quantity += $request->quantity; // Increment quantity
                $cartItem->price = $request->price; // Update price
                $cartItem->save();
            } else {
                // If the cart item does not exist, create a new one
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                ]);
            }

            return response()->json($cartItem, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to add item to cart. ' . $e->getMessage()], 500);
        }
    }

    // Update item quantity
    public function updateItem(Request $request, $itemId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cartItem = CartItem::findOrFail($itemId);
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return response()->json($cartItem);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to update item. ' . $e->getMessage()], 500);
        }
    }

    // Remove item from cart
    public function removeItem($itemId)
    {
        try {
            $cartItem = CartItem::findOrFail($itemId);
            $cartItem->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to remove item. ' . $e->getMessage()], 500);
        }
    }

    // Helper function to get the cart
    protected function getUserCart(Request $request)
    {
        // Check if the user is authenticated
        if ($request->user()) {
            // For authenticated users
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        } else {
            // For guest users, use session ID
            $sessionId = session()->getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }
}
