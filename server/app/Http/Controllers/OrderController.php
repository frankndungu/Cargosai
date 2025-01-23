<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; // Ensure this is included
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\AdminOrderMail;
use Illuminate\Database\QueryException;
use Exception;

class OrderController extends Controller
{
    // Get all orders (for admins) or orders for the authenticated user
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $orders = Order::with('user') // Include user details
                ->orderBy('id')
                ->get()
                ->map(function ($order) {
                    $order->formatted_id = str_pad($order->id, 3, '0', STR_PAD_LEFT);
                    return $order;
                });
        } else {
            $orders = Order::with('user') // Include user details
                ->where('user_id', $user->id)
                ->orderBy('id')
                ->get()
                ->map(function ($order) {
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

        $order = Order::with(['orderItems.product', 'user', 'shippingAddress']) // Add shippingAddress
            ->where('id', $id)
            ->where(function ($query) use ($user) {
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

            // Validation
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.price' => 'required|numeric',
                'items.*.quantity' => 'required|integer|min:1',
                'shipping_fee' => 'required|numeric',
                'total_price' => 'required|numeric',
                'status' => 'required|string|in:Pending,Canceled,Shipped,Delivered',
                'payment_status' => 'required|string|in:Pending,Completed,Failed,Refund',
                'shipping_address' => 'required|array',
                'shipping_address.address1' => 'required|string',
                'shipping_address.country' => 'required|string',
                'shipping_address.state' => 'nullable|string',
                'shipping_address.city' => 'nullable|string',
                'shipping_address.postal_code' => 'nullable|string',
                'guest_email' => 'nullable|email|required_without:user',
                'guest_name' => 'nullable|string|required_without:user',
                'guest_phone' => 'nullable|string|required_without:user',
            ]);

            // Create or Update Shipping Address
            $shippingAddress = ShippingAddress::create([
                'user_id' => $user ? $user->id : null, // Use user ID if authenticated, otherwise null
                'address1' => $request->shipping_address['address1'],
                'country' => $request->shipping_address['country'],
                'state' => $request->shipping_address['state'],
                'city' => $request->shipping_address['city'],
                'postal_code' => $request->shipping_address['postal_code'],
            ]);

            // Create Order
            $order = Order::create([
                'user_id' => $user ? $user->id : null, // Use user ID if authenticated
                'shipping_fee' => $request->shipping_fee,
                'total_price' => $request->total_price,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'reference' => uniqid('order_'),
                'shipping_address_id' => $shippingAddress->id,
                'guest_email' => $request->guest_email, // Guest details
                'guest_name' => $request->guest_name,
                'guest_phone' => $request->guest_phone,
            ]);

            // Create Order Items
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            // Load Order with Relations
            $order = Order::with(['orderItems.product', 'user', 'shippingAddress'])->find($order->id);

            // Send Confirmation Email (to guest or authenticated user)
            $recipient = $user ? $user->email : $request->guest_email;
            Mail::to($recipient)->send(new OrderConfirmationMail($order));

            // Notify Admins
            $adminEmails = ['support@maasaimarketonline.com', 'sisinei@maasaimarketonline.com'];
            Mail::to($adminEmails)->send(new AdminOrderMail($order));

            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id,
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

            // Send the email notification
            $this->sendOrderStatusUpdatedEmail($order);

            return response()->json(['message' => 'Order has been canceled successfully', 'order' => $order], 200);
        }

        // Update status and payment status for other status changes
        $order->status = $request->status;

        // If payment status is provided, update it
        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        // Send the email notification
        $this->sendOrderStatusUpdatedEmail($order);

        return response()->json(['message' => 'Order status and payment status updated successfully', 'order' => $order], 200);
    }

    // Send Order Status Updated Email to both the authenticated user and the guest (if applicable)
    private function sendOrderStatusUpdatedEmail($order)
    {
        // Send to the authenticated user if available
        if ($order->user) {
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));
        }

        // Send to the guest email if available
        if ($order->guest_email) {
            Mail::to($order->guest_email)->send(new OrderStatusUpdatedMail($order));
        }
    }

    // Get total number of orders
    public function getTotalOrders()
    {
        try {
            // Fetch the total number of orders
            $totalOrders = Order::count();

            return response()->json([
                'total_orders' => $totalOrders,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the total number of orders',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Get total number of completed orders
    public function getCompletedOrdersCount()
    {
        try {
            // Fetch the total number of completed orders
            $completedOrdersCount = Order::where('payment_status', 'Completed')->count();

            return response()->json([
                'completed_orders_count' => $completedOrdersCount,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching completed orders count',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Get the monthly percentage change in completed orders
    public function getCompletedOrdersChange()
    {
        try {
            // Fetch the number of completed orders for the current month
            $currentMonthCompletedOrders = Order::where('payment_status', 'Completed')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();

            // Fetch the number of completed orders for the previous month
            $lastMonthCompletedOrders = Order::where('payment_status', 'Completed')
                ->whereYear('created_at', now()->subMonth()->year)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->count();

            // Calculate the percentage change
            $percentageChange = $lastMonthCompletedOrders > 0
                ? (($currentMonthCompletedOrders - $lastMonthCompletedOrders) / $lastMonthCompletedOrders) * 100
                : ($currentMonthCompletedOrders > 0 ? 100 : 0);

            return response()->json([
                'current_month_completed_orders' => $currentMonthCompletedOrders,
                'last_month_completed_orders' => $lastMonthCompletedOrders,
                'percentage_change' => round($percentageChange, 2),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while calculating the completed orders change percentage',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    // Get the 5 most recent sales
    public function getRecentSales()
    {
        try {
            // Fetch the 5 most recent orders with 'Completed' payment status
            $recentSales = Order::select('total_price', 'user_id', 'payment_status') // Select total_price, user_id, and payment_status
                ->with([
                    'user:id,name,email' // Include only the user's id, name, and email
                ])
                ->where('payment_status', 'Completed') // Only fetch orders with 'Completed' payment status
                ->orderBy('created_at', 'desc') // Order by creation date, most recent first
                ->limit(5) // Limit to 5 orders
                ->get();

            // Map to include name, email, payment_status, and total_price
            $sales = $recentSales->map(function ($order) {
                return [
                    'name' => $order->user->name ?? 'Unknown',
                    'email' => $order->user->email ?? 'Unknown',
                    'payment_status' => $order->payment_status, // Include payment_status
                    'total_price' => $order->total_price,
                ];
            });

            return response()->json([
                'recent_sales' => $sales,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching recent sales',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Get percentage change in the total number of orders
    public function getOrderChange()
    {
        try {
            // Fetch the number of orders for the current month
            $currentMonthOrders = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();

            // Fetch the number of orders for the previous month (from the 1st to the last day)
            $lastMonthOrders = Order::whereYear('created_at', now()->subMonth()->year)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->count();

            // Calculate percentage change
            $percentageChange = $lastMonthOrders > 0
                ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
                : ($currentMonthOrders > 0 ? 100 : 0);

            return response()->json([
                'current_month_orders' => $currentMonthOrders,
                'last_month_orders' => $lastMonthOrders,
                'percentage_change' => round($percentageChange, 2),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while calculating order change percentage',
                'error' => $e->getMessage(),
            ], 500);
        }
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
