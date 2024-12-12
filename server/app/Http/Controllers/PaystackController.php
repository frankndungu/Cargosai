<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackController extends Controller
{
    // Fixed exchange rate (USD to KES)
    const USD_TO_KES_RATE = 145;

    public function initializePayment(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric', // Amount in USD
        ]);

        try {
            // Convert USD to KES
            $amountInKes = $validatedData['amount'] * self::USD_TO_KES_RATE;

            // Convert KES to Kobo (Paystack requires the amount in the smallest currency unit)
            $amountInKobo = $amountInKes * 100;

            $paystackUrl = config('paystack.payment_url') . '/transaction/initialize';

            $response = Http::withOptions([
                'verify' => 'C:\certificates\cacert.pem'
            ])
            ->withToken(config('paystack.secret_key'))
            ->post($paystackUrl, [
                'email' => $validatedData['email'],
                'amount' => $amountInKobo,
                'callback_url' => route('paystack.callback'),
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            // Log the error response
            Log::error('Paystack Payment Initialization Failed', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return response()->json(['message' => 'Payment initialization failed.'], 500);
        } catch (\Exception $e) {
            Log::error('Paystack Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function handleCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect('/checkout')->with('error', 'Payment reference not found.');
        }

        try {
            $paystackUrl = config('paystack.payment_url') . "/transaction/verify/{$reference}";

            $response = Http::withOptions([
                'verify' => 'C:\certificates\cacert.pem'
            ])
            ->withToken(config('paystack.secret_key'))
            ->get($paystackUrl);

            if ($response->successful() && $response->json('data.status') === 'success') {
                // Update order status in the database
                // Example: Order::where('reference', $reference)->update(['status' => 'paid']);

                return redirect('/')->with('success', 'Payment successful!');
            }

            // Log the error response
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
