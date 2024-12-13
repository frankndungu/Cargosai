<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class PaystackController extends Controller
{
    // Conversion rate from USD to KES (example rate; update as needed)
    const USD_TO_KES_RATE = 145.0;

    /**
     * Initialize a payment via Paystack.
     */
    public function initializePayment(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id', // Validate that the order_id exists in the orders table
        ]);

        $order = Order::findOrFail($validatedData['order_id']); // Fetch the order from the database

        try {
            // Ensure the reference is generated for the order if not already set
            if (!$order->reference) {
                $order->reference = 'order_' . uniqid();
                $order->save();
            }

            // Convert USD to KES
            $amountInKes = $order->total_price * self::USD_TO_KES_RATE;

            // Convert KES to Kobo
            $amountInKobo = $amountInKes * 100;

            // Paystack payment initialization endpoint
            $paystackUrl = config('paystack.payment_url') . '/transaction/initialize';

            // Make the API request to Paystack
            $response = Http::withOptions([
                'verify' => 'C:\\certificates\\cacert.pem' // Path to your CA certificate file
            ])
            ->withToken(config('paystack.secret_key')) // Include the Paystack secret key
            ->post($paystackUrl, [
                'email' => $order->user->email, // Use the email associated with the order's user
                'amount' => $amountInKobo,      // Amount in Kobo
                'reference' => $order->reference, // Pass the order reference
                'callback_url' => route('paystack.callback'), // Set the callback URL
            ]);

            // Check if the Paystack response is successful
            if ($response->successful()) {
                return response()->json($response->json()); // Return Paystack response
            }

            // Log error if the response is not successful
            Log::error('Paystack Payment Initialization Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return response()->json(['message' => 'Payment initialization failed.'], 500);
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('Paystack Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Handle the Paystack callback to verify payment.
     */
    public function handleCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect('/checkout')->with('error', 'Payment reference not found.');
        }

        try {
            // Paystack verification endpoint
            $paystackUrl = config('paystack.payment_url') . "/transaction/verify/{$reference}";

            // Make the API request to verify the transaction
            $response = Http::withOptions([
                'verify' => 'C:\\certificates\\cacert.pem' // Path to your CA certificate file
            ])
            ->withToken(config('paystack.secret_key')) // Include the Paystack secret key
            ->get($paystackUrl);

            if ($response->successful() && $response->json('data.status') === 'success') {
                $order = Order::where('reference', $reference)->first();

                if ($order) {
                    $order->payment_status = 'Completed';
                    $order->status = 'Paid';
                    $order->save();
                }

                return redirect('/')->with('success', 'Payment successful!');
            }

            // Log error if verification fails
            Log::error('Paystack Verification Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return redirect('/checkout')->with('error', 'Payment verification failed.');
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('Paystack Verification Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect('/checkout')->with('error', 'An unexpected error occurred during payment verification.');
        }
    }
}
