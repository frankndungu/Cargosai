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
        $orders = Order::where('user_id', $user->id)->get();

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
    // Create a new order
    public function create(Request $request)
    {
        $user = $request->user();

        // Validate the request data
        $request->validate([
            'items' => 'required|array', // Ensure items are provided
            'total_price' => 'required|numeric', // Ensure a total amount is provided
            'status' => 'required|string|max:255', // Ensure status is provided
        ]);

        // Create a new order
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $request->total_price, // Updated to match migration
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

        $request->validate([
            'status' => 'required|string|max:255',
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
