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
    public function getRevenueSummary(Request $request)
    {
        try {
            // Optional date filters
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');

            $query = Payment::where('status', 'Completed');

            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }

            $totalRevenue = $query->sum('amount');
            $totalPayments = $query->count();

            return response()->json([
                'total_revenue' => $totalRevenue,
                'total_payments' => $totalPayments,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while calculating revenue summary.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get monthly sales data based on completed payments
     */
    public function getMonthlySalesData(Request $request)
    {
        try {
            // Optional year filter
            $year = $request->query('year', date('Y')); // Default to the current year

            $monthlySales = Order::selectRaw('SUM(payments.amount) as total_sales, EXTRACT(MONTH FROM orders.created_at) as month')
                ->join('payments', 'orders.id', '=', 'payments.order_id')
                ->where('orders.payment_status', 'Completed')
                ->whereYear('orders.created_at', $year) // Filter by year
                ->groupBy(DB::raw('EXTRACT(MONTH FROM orders.created_at)'))
                ->orderBy(DB::raw('EXTRACT(MONTH FROM orders.created_at)'), 'asc')
                ->get();

            $salesData = [
                'labels' => [],
                'data' => [],
            ];

            foreach ($monthlySales as $sales) {
                $salesData['labels'][] = date('F', mktime(0, 0, 0, $sales->month, 10));
                $salesData['data'][] = $sales->total_sales;
            }

            return response()->json($salesData, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching monthly sales data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get daily sales data based on completed payments
     */
    public function getDailySalesData(Request $request)
    {
        try {
            $startDate = $request->query('start_date', now()->subWeek()->format('Y-m-d')); // Default to last week
            $endDate = $request->query('end_date', now()->format('Y-m-d')); // Default to today

            $dailySales = Order::selectRaw('SUM(payments.amount) as total_sales, DATE(orders.created_at) as date')
                ->join('payments', 'orders.id', '=', 'payments.order_id')
                ->where('orders.payment_status', 'Completed')
                ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(orders.created_at)'))
                ->orderBy(DB::raw('DATE(orders.created_at)'), 'asc')
                ->get();

            return response()->json($dailySales, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching daily sales data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper function
     */
    private function getDateRange($period)
    {
        switch ($period) {
            case 'last_week':
                return [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()];
            case 'last_month':
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
            default:
                return [now()->startOfMonth(), now()];
        }
    }

}
