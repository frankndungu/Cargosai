<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Get all orders for the authenticated user
    public function index(Request $request)
    {
        $user = $request->user();

        // Fetch orders and order them by ID in ascending order
        $orders = Order::where('user_id', $user->id)
            ->orderBy('id') // Orders will be sorted by ID (1, 2, 3, ...)
            ->get()
            ->map(function ($order) {
                // Format the order ID as a three-digit string
                $order->formatted_id = str_pad($order->id, 3, '0', STR_PAD_LEFT);
                return $order;
            });

        return response()->json($orders, 200);
    }

    // Get a specific order by ID for the authenticated user
    public function show($id, Request $request)
    {
        $user = $request->user();
        $order = Order::where('id', $id)->where('user_id', $user->id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    // Create a new order
    public function create(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'items' => 'required|array',
            'total_price' => 'required|numeric',
            'status' => 'required|string|in:Pending,Canceled,Shipped,Delivered',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $request->total_price,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }

    // Update the status of a specific order
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::where('id', $id)->where('user_id', $user->id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Check if the order is already shipped, delivered, or canceled
        if (in_array($order->status, ['Shipped', 'Delivered', 'Canceled'])) {
            return response()->json(['message' => 'Cannot cancel or change status of this order.'], 400);
        }

        // Only allow status update to 'Canceled' for the user
        $request->validate([
            'status' => 'required|string|in:Canceled', // Allow only 'Canceled' status for users
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order], 200);
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
