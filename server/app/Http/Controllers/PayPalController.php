<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use Exception;

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
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);

            $amount = $validated['amount'];
            $returnUrl = url('/api/paypal/success');
            $cancelUrl = url('/api/paypal/cancel');

            $order = $this->payPalService->createOrder($amount, $returnUrl, $cancelUrl, 'USD');

            if (isset($order['id']) && isset($order['links'])) {
                $approvalLink = collect($order['links'])
                    ->firstWhere('rel', 'approve');

                if ($approvalLink) {
                    return response()->json(['approval_url' => $approvalLink['href']]);
                }
            }

            throw new Exception('Unable to create PayPal order');
        } catch (Exception $e) {
            Log::error('Create Order Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        try {
            $token = $request->query('token');
            $PayerID = $request->query('PayerID');

            if (!$token || !$PayerID) {
                throw new Exception('Invalid request parameters');
            }

            // Log the incoming request parameters
            Log::info('PayPal Success Callback:', [
                'token' => $token,
                'PayerID' => $PayerID
            ]);

            // Attempt to capture the payment
            $captureResponse = $this->payPalService->capturePayment($token);

            // Check if payment was successful
            if (!empty($captureResponse['status']) && $captureResponse['status'] === 'COMPLETED') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful',
                    'data' => $captureResponse
                ]);
            }

            // If we reach here, something went wrong with the capture
            Log::error('Payment capture failed:', $captureResponse);
            throw new Exception('Payment capture failed');

        } catch (Exception $e) {
            Log::error('Payment Success Handler Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Payment could not be captured: ' . $e->getMessage()
            ], 500);
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
