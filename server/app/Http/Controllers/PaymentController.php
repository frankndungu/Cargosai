<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

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
            // Calculate total revenue for completed payments only
            $totalRevenue = Payment::where('status', 'Completed')->sum('amount');

            // Count total number of completed payments
            $totalPayments = Payment::where('status', 'Completed')->count();

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

    /**
     * Get monthly sales data based on completed payments
     */
    public function getMonthlySalesData()
    {
        try {
            // Calculate the total amount paid for completed orders each month
            $monthlySales = Order::selectRaw('SUM(payments.amount) as total_sales, EXTRACT(MONTH FROM orders.created_at) as month')
                ->join('payments', 'orders.id', '=', 'payments.order_id')  // Join the payments table
                ->where('orders.payment_status', 'Completed')  // Filter by completed payment status
                ->groupBy(DB::raw('EXTRACT(MONTH FROM orders.created_at)'))
                ->orderBy(DB::raw('EXTRACT(MONTH FROM orders.created_at)'), 'asc')
                ->get();
    
            // Prepare the data for the chart (labels and values)
            $salesData = [
                'labels' => [],
                'data' => []
            ];
    
            // Fill the data arrays
            foreach ($monthlySales as $sales) {
                $salesData['labels'][] = date('F', mktime(0, 0, 0, $sales->month, 10));  // Get month name
                $salesData['data'][] = $sales->total_sales;  // Total amount paid for completed orders
            }
    
            return response()->json($salesData, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching monthly sales data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }   
}
