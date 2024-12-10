<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        if (!$signature || !$this->isValidSignature($payload, $signature)) {
            return response()->json(['status' => 'Invalid signature'], 400);
        }

        // Parse the webhook payload
        $event = json_decode($payload);

        if ($event->event === 'charge.success') {
            $this->handleSuccessfulPayment($event->data);
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function isValidSignature($payload, $signature)
    {
        $secretKey = config('PAYSTACK_SECRET_KEY'); // Add your Paystack secret key here
        $generatedSignature = hash_hmac('sha512', $payload, $secretKey);

        return $signature === $generatedSignature;
    }

    private function handleSuccessfulPayment($paymentData)
    {
        // Extract payment details
        $reference = $paymentData->reference;
        $amount = $paymentData->amount / 100; // Convert from kobo to the original currency
        $email = $paymentData->customer->email;

        // Log the payment (optional for debugging)
        Log::info("Payment successful: Ref: $reference, Amount: $amount, Email: $email");

        // Update the order status in your database
        // Example: Order::where('transaction_reference', $reference)->update(['status' => 'paid']);
    }
}
