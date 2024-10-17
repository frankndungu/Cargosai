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
        $order = Order::with('orderItems') // Assuming 'orderItems' relationship exists
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }
}
