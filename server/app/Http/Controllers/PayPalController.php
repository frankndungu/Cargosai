<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\AdminOrderMail;
use App\Mail\OrderPendingMail;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class PayPalController extends Controller
{
    protected $payPalService;

    public function __construct(PayPalService $payPalService)
    {
        $this->payPalService = $payPalService;
    }

    public function createOrder(Request $request)
    {
        try {
            $user = $request->user();

            // Updated validation rules - removed status and payment_status
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.price' => 'required|numeric',
                'items.*.quantity' => 'required|integer|min:1',
                'shipping_fee' => 'required|numeric',
                'total_price' => 'required|numeric',
                'shipping_address' => 'required|array',
                'shipping_address.address1' => 'required|string',
                'shipping_address.country' => 'required|string',
                'shipping_address.state' => 'nullable|string',
                'shipping_address.city' => 'nullable|string',
                'shipping_address.postal_code' => 'nullable|string',
                'billing_address' => 'required|array',
                'billing_address.address1' => 'required|string',
                'billing_address.country' => 'required|string',
                'billing_address.state' => 'nullable|string',
                'billing_address.city' => 'nullable|string',
                'billing_address.postal_code' => 'nullable|string',
                'guest_email' => 'nullable|email|required_without:user',
                'guest_name' => 'nullable|string|required_without:user',
                'guest_phone' => 'nullable|string|required_without:user',
            ]);

            // Create or Update Shipping Address
            $shippingAddress = ShippingAddress::create([
                'user_id' => $user ? $user->id : null,
                'guest_id' => $user ? null : Str::uuid(),
                'address1' => $request->shipping_address['address1'],
                'country' => $request->shipping_address['country'],
                'state' => $request->shipping_address['state'],
                'city' => $request->shipping_address['city'],
                'postal_code' => $request->shipping_address['postal_code'],
            ]);

            // Create or Update Billing Address
            $billingAddress = BillingAddress::create([
                'user_id' => $user ? $user->id : null,
                'guest_id' => $user ? null : Str::uuid(),
                'address1' => $request->billing_address['address1'],
                'country' => $request->billing_address['country'],
                'state' => $request->billing_address['state'],
                'city' => $request->billing_address['city'],
                'postal_code' => $request->billing_address['postal_code'],
            ]);

            // Create the Order with default status values for PayPal
            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'guest_id' => $user ? null : Str::uuid(),
                'guest_email' => $request->guest_email,
                'guest_name' => $request->guest_name,
                'guest_phone' => $request->guest_phone,
                'shipping_fee' => $request->shipping_fee,
                'total_price' => $request->total_price,
                'status' => 'Pending',
                'payment_status' => 'Pending',
                'payment_method' => 'PayPal', // Add payment method
                'reference' => 'PAYPAL-' . uniqid(),
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $billingAddress->id,
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

            // Generate PayPal order
            $returnUrl = url('/api/paypal/success?order_id=' . $order->id);
            $cancelUrl = url('/api/paypal/cancel?order_id=' . $order->id);
            $paypalOrder = $this->payPalService->createOrder($order->total_price, $returnUrl, $cancelUrl, 'USD');

            if (!isset($paypalOrder['id']) || !isset($paypalOrder['links'])) {
                throw new Exception('Unable to create PayPal order');
            }

            $approvalLink = collect($paypalOrder['links'])->firstWhere('rel', 'approve');
            if (!$approvalLink) {
                throw new Exception('PayPal approval URL not found');
            }

            // Load Order with Relations
            $order = Order::with(['orderItems.product', 'user', 'shippingAddress', 'billingAddress'])->find($order->id);

            // Do NOT send any emails at order creation - wait for payment completion

            // Return Success Response with PayPal approval URL
            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id,
                'order' => $order,
                'approval_url' => $approvalLink['href']
            ], 201);

        } catch (QueryException $qe) {
            Log::error('Database Error in PayPal Order Creation: ' . $qe->getMessage());
            return response()->json([
                'message' => 'Database error during order creation',
                'error' => $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('Error in PayPal Order Creation: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during order creation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        try {
            $token = $request->query('token');
            $payerID = $request->query('PayerID');
            $orderId = $request->query('order_id');

            if (!$token || !$payerID || !$orderId) {
                throw new Exception('Invalid request parameters');
            }

            Log::info('PayPal Success Callback:', [
                'token' => $token,
                'PayerID' => $payerID,
                'order_id' => $orderId
            ]);

            $captureResponse = $this->payPalService->capturePayment($token);

            if (!empty($captureResponse['status']) && $captureResponse['status'] === 'COMPLETED') {
                // Update order status
                $order = Order::findOrFail($orderId);
                $order->update([
                    'payment_status' => 'Completed',
                    'status' => 'Pending', // Keep status as Pending for processing
                    'payment_method' => 'PayPal'
                ]);

                // Create a Payment record
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'PayPal',
                    'reference' => $captureResponse['id'], // PayPal transaction ID
                    'status' => 'Completed',
                    'amount' => $order->total_price,
                ]);

                // Reload order with relationships for email
                $order = Order::with(['orderItems.product', 'user', 'shippingAddress', 'billingAddress'])->find($orderId);

                // Send confirmation email to customer
                $recipient = $order->user ? $order->user->email : $order->guest_email;
                Mail::to($recipient)->send(new OrderConfirmationMail($order));

                // Notify admins
                $adminEmails = ['support@maasaimarketonline.com', 'sisinei@maasaimarketonline.com'];
                Mail::to($adminEmails)->send(new AdminOrderMail($order));

                // Redirect to frontend order success page
                return redirect(config('app.frontend_url') . '/order/success?order_id=' . $order->id)
                    ->with('success', 'Payment successful!');
            }

            Log::error('Payment capture failed:', $captureResponse);
            throw new Exception('Payment capture failed');
        } catch (Exception $e) {
            Log::error('Payment Success Handler Error: ' . $e->getMessage());
            
            // Redirect to frontend with error message
            return redirect(config('app.frontend_url') . '/payment/failed')
                ->with('error', 'Payment could not be captured: ' . $e->getMessage());
        }
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

    public function cancel(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Payment was cancelled'
        ]);
    }
}
