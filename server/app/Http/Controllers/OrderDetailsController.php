<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderDetailsController extends Controller
{
    // Get detailed information about a specific order
    public function show($orderId, Request $request)
    {
        try {
            $user = $request->user();

            // If no user is authenticated, return unauthorized
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // For admin, remove user_id constraint
            $query = $user->role === 'admin' 
                ? Order::where('id', $orderId)
                : Order::where('id', $orderId)->where('user_id', $user->id);

            $order = $query->with(['orderItems', 'orderItems.product'])->first();

            if (!$order) {
                // Log additional context for debugging
                Log::warning('Order not found', [
                    'order_id' => $orderId, 
                    'user_id' => $user->id,
                    'user_role' => $user->role
                ]);

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
                        'name' => optional($item->product)->name ?? 'Unknown Product', 
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'image_url' => optional($item->product)->image_url ?? null, 
                        'description' => optional($item->product)->description ?? null,
                    ];
                }),
            ];

            return response()->json($formattedOrder, 200);

        } catch (\Exception $e) {
            // Log the full exception for server-side debugging
            Log::error('Order details retrieval error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'An unexpected error occurred'], 500);
        }
    }
}