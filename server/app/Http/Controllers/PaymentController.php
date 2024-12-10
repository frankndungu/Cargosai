<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaystackService;

class PaymentController extends Controller
{
    protected $paystackService;

    /**
     * Constructor to inject PaystackService.
     *
     * @param PaystackService $paystackService
     */
    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Initialize payment and redirect to Paystack.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initializePayment(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ]);

        $response = $this->paystackService->initializePayment($validated['email'], $validated['amount']);

        if (isset($response['status']) && $response['status'] === true) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    /**
     * Verify payment after redirection.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return response()->json(['status' => false, 'message' => 'No payment reference supplied.'], 400);
        }

        $response = $this->paystackService->verifyPayment($reference);

        if (isset($response['data']['status']) && $response['data']['status'] === 'success') {
            // Handle successful payment (e.g., update order status in the database)
            return response()->json(['status' => true, 'message' => 'Payment successful.']);
        }

        return response()->json(['status' => false, 'message' => 'Payment verification failed.']);
    }
}
