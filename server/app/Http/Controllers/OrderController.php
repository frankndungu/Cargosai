<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem; // Ensure this is included
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;

class OrderController extends Controller
{
    // Get all orders (for admins) or orders for the authenticated user
    public function index(Request $request)
    {
        $user = $request->user();

        // Check if the user is an admin
        if ($user->role === 'admin') {
            // Fetch all orders for admin
            $orders = Order::orderBy('id') // Orders will be sorted by ID (1, 2, 3, ...)
                ->get()
                ->map(function ($order) {
                    // Format the order ID as a three-digit string
                    $order->formatted_id = str_pad($order->id, 3, '0', STR_PAD_LEFT);
                    return $order;
                });
        } else {
            // Fetch orders for the authenticated user
            $orders = Order::where('user_id', $user->id)
                ->orderBy('id') // Orders will be sorted by ID (1, 2, 3, ...)
                ->get()
                ->map(function ($order) {
                    // Format the order ID as a three-digit string
                    $order->formatted_id = str_pad($order->id, 3, '0', STR_PAD_LEFT);
                    return $order;
                });
        }

        return response()->json($orders, 200);
    }

    // Get a specific order by ID (for both user and admin)
    public function show($id, Request $request)
    {
        $user = $request->user();

        // Admin can access any order, regular users can only access their own
        $order = Order::where('id', $id)
            ->where(function ($query) use ($user) {
                // Allow admin to view all orders
                if ($user->role === 'user') {
                    $query->where('user_id', $user->id);
                }
            })
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    // Create a new order
    public function create(Request $request)
    {
        try {
            $user = $request->user();
    
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.price' => 'required|numeric',
                'items.*.quantity' => 'required|integer|min:1',
                'total_price' => 'required|numeric',
                'status' => 'required|string|in:Pending,Canceled,Shipped,Delivered',
                'payment_status' => 'required|string|in:Pending,Completed,Failed,Refund', // Validate payment status
            ]);
    
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $request->total_price,
                'status' => $request->status,
                'payment_status' => $request->payment_status, // Add payment status from request
                'reference' => uniqid('order_'), // Generate a unique reference
            ]);
    
            // Create each order item
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'], // Calculate total
                ]);
            }
    
            // Return order ID along with other details
            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id, // Explicitly return order_id
                'order' => $order,
            ], 201);
    
        } catch (QueryException $qe) {
            return response()->json([
                'message' => 'Database error during order creation',
                'error' => $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred during order creation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    // Update the status of a specific order
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user(); // Get the authenticated user
        $order = Order::where('id', $id)->first(); // Admin can update any order, so no user_id check needed.
    
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        // Prevent the user from canceling the order if its status is not 'Pending'
        if ($user->role === 'user' && $order->status !== 'Pending') {
            return response()->json(['message' => 'You can only cancel orders with "Pending" status.'], 400);
        }
    
        // Validate status change for users and admins
        if ($user->role !== 'admin' && in_array($order->status, ['Shipped', 'Delivered', 'Canceled'])) {
            return response()->json(['message' => 'Cannot change status of this order.'], 400);
        }
    
        // Validate the request body
        $request->validate([
            'status' => 'required|string|in:Pending,Canceled,Shipped,Delivered', // Only admin can update this to other statuses
            'payment_status' => 'nullable|string|in:Pending,Completed,Failed,Refund', // Admin can update payment status
        ]);
    
        // Check if the user is attempting to cancel the order
        if ($request->status === 'Canceled' && $order->status === 'Pending') {
            // Update the order status to 'Canceled'
            $order->status = 'Canceled';
            $order->save();
            return response()->json(['message' => 'Order has been canceled successfully', 'order' => $order], 200);
        }
    
        // Update status and payment status for other status changes
        $order->status = $request->status;
    
        // If payment status is provided, update it
        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }
    
        $order->save();
    
        return response()->json(['message' => 'Order status and payment status updated successfully', 'order' => $order], 200);
    }
    
    // Delete a specific order
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        $order = Order::where('id', $id)->where('user_id', $user->id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
