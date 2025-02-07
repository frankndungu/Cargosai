<?php

namespace App\Services;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Facades\PayPal;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = PayPal::setProvider();
    }

    public function createOrder($amount, $returnUrl, $cancelUrl, $currency = 'USD')
    {
        try {
            $this->provider->setApiCredentials(config('paypal'));
            $this->provider->getAccessToken();

            $order = $this->provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "reference_id" => "default",
                        "amount" => [
                            "currency_code" => $currency,
                            "value" => number_format($amount, 2, '.', '')
                        ]
                    ]
                ],
                "application_context" => [
                    "return_url" => $returnUrl,
                    "cancel_url" => $cancelUrl,
                ]
            ]);

            Log::info('PayPal Order Created:', $order);
            return $order;
        } catch (\Exception $e) {
            Log::error('PayPal Order Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function capturePayment($orderId)
    {
        try {
            $this->provider->setApiCredentials(config('paypal'));
            $this->provider->getAccessToken();
            
            $result = $this->provider->capturePaymentOrder($orderId);
            Log::info('PayPal Payment Captured:', $result);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('PayPal Capture Error: ' . $e->getMessage());
            throw $e;
        }
    }
}