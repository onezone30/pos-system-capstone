<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\ProductPrices;
use App\Models\SalesHistory;
use App\Models\User;
use App\Services\ForecastServices;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(ForecastServices $forecast)
    {
        $user = Auth::user();

        $forecast7 = $forecast->forecastRevenueSeries(60, 7, 0.5);
        $forecast30 = $forecast->forecastRevenueSeries(60, 30, 0.5);

        // ===== BASIC METRICS =====
        $todaySales   = Order::whereDate('created_at', today())->sum('amount_paid');
        $weekSales    = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount_paid');
        $monthSales   = Order::whereMonth('created_at', now()->month)->sum('amount_paid');
        $grossRevenue = Order::sum('amount_paid');
        $averageTransaction = Order::avg('amount_paid');

        // ===== TOP 5 PRODUCTS =====
        $topProducts = OrderItems::selectRaw('product_id, SUM(quantity) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('product')
            ->get();

        // ===== PAYMENT METHODS =====
        $paymentMethods = Order::selectRaw('payment_method, COUNT(*) as total')
            ->groupBy('payment_method')
            ->get();

        // ===== RECENT TRANSACTIONS =====
        $recentTransactions = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // ===== INVENTORY =====
        $lowStock = ProductPrices::with('product')
            ->where('quantity_stock', '<=', 10)
            ->where('quantity_stock', '>', 0)
            ->orderBy('quantity_stock', 'asc')
            ->get();

        $outOfStock = ProductPrices::with('product')
            ->where('quantity_stock', 0)
            ->get();

        // ===== SALES TREND (ACTUAL) =====
        $salesTrends = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartDates  = $salesTrends->pluck('date')->map(fn($d) => Carbon::parse($d)->format('D'))->toArray();
        $chartTotals = $salesTrends->pluck('total')->toArray();

        // ===== FORECASTING =====
        $overallForecast = $forecast->forecastRevenueSeries(historyDays: 60, horizon: 7, alpha: 0.5);
        

        $forecastLabels = $overallForecast['dates'];
        $forecastData   = $overallForecast['values'];

        $forecastedStockOuts = $forecast->predictStockOuts(
            30,    // horizon
            0.25,  // alpha
            60,    // historyDays
            7      // thresholdDays
        );
        $forecastedStockOutCount = count($forecastedStockOuts);

        // ===== EMPLOYEE SALES =====
        $employeeSales = User::where('role', 'cashier')
            ->withSum(['orders as total_sales' => function ($q) {
                $q->selectRaw('COALESCE(SUM(total_amount), 0)');
            }], 'total_amount')
            ->get();

        $employeeNames  = $employeeSales->pluck('name')->toArray();
        $employeeTotals = $employeeSales->pluck('total_sales')->toArray();
        $topSeller      = $employeeSales->sortByDesc('total_sales')->first();

        return view('admin.index', [
            'user' => $user,
            'todaySales' => $todaySales,
            'weekSales' => $weekSales,
            'monthSales' => $monthSales,
            'grossRevenue' => $grossRevenue,
            'averageTransaction' => $averageTransaction,
            'topProducts' => $topProducts,
            'paymentMethods' => $paymentMethods,
            'recentTransactions' => $recentTransactions,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'chartDates' => $chartDates,
            'chartTotals' => $chartTotals,
            'forecastLabels' => $forecastLabels,
            'forecastData' => $forecastData,
            'employeeSales' => $employeeSales,
            'employeeNames' => $employeeNames,
            'employeeTotals' => $employeeTotals,
            'topSeller' => $topSeller,
            'overallForecast' => $overallForecast,
            'forecastedStockOuts' => $forecastedStockOuts,
            'forecastedStockOutCount' => $forecastedStockOutCount,
            'forecast7' => $forecast7,
            'forecast30' => $forecast30
        ])->with([
            'type' => 'success',
            'message' => 'Dashboard loaded'
        ]);
    }
}
