<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Get all payments
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    // Get a specific payment by ID
    public function show($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json($payment);
    }

    /**
     * Get total revenue from payments
     */
    public function getRevenueSummary()
{
    try {
        // Calculate total revenue
        $totalRevenue = Payment::sum('amount');

        // Count total number of payments
        $totalPayments = Payment::count();

        // Log the revenue calculation process
        Log::info('Revenue summary calculated successfully', [
            'total_revenue' => $totalRevenue,
            'total_payments' => $totalPayments,
        ]);

        return response()->json([
            'total_revenue' => $totalRevenue,
            'total_payments' => $totalPayments,
        ]);
    } catch (\Exception $e) {
        // Log the error details
        Log::error('Error calculating revenue summary', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Return an error response
        return response()->json([
            'message' => 'An error occurred while calculating the revenue summary.',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
}

}
