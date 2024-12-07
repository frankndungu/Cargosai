<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderDetailsController extends Controller
{
    // Get detailed information about a specific order
    public function show($orderId, Request $request)
    {
        $user = $request->user();
        $order = Order::with('orderItems.product') // Assuming 'orderItems' relationship exists and has a 'product' relationship
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Format the response to match your dummy data structure
        $formattedOrder = [
            'id' => $order->id,
            'created_at' => $order->created_at,
            'status' => $order->status,
            'total_price' => $order->total_price,
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name, // Assuming the product has a name attribute
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'image_url' => $item->product->image_url, // Assuming the product has an image_url attribute
                    'description' => $item->product->description, // Assuming the product has a description attribute
                ];
            }),
        ];

        return response()->json($formattedOrder, 200);
    }
}
