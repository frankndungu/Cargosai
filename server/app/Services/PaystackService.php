<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    /**
     * Initialize a payment.
     *
     * @param string $email
     * @param float $amount
     * @return array
     */
    public function initializePayment(string $email, float $amount): array
    {
        $url = config('paystack.payment_url') . '/transaction/initialize';

        $response = Http::withToken(config('paystack.secret_key'))->post($url, [
            'email' => $email,
            'amount' => $amount * 100, // Convert amount to kobo (smallest currency unit for NGN)
        ]);

        return $response->json();
    }

    /**
     * Verify a payment.
     *
     * @param string $reference
     * @return array
     */
    public function verifyPayment(string $reference): array
    {
        $url = config('paystack.payment_url') . '/transaction/verify/' . $reference;

        $response = Http::withToken(config('paystack.secret_key'))->get($url);

        return $response->json();
    }
}
