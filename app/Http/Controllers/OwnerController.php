<?php

namespace App\Http\Controllers;

use App\Models\InventoryLogs;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\ProductPrices;
use App\Models\User;
use App\Services\ForecastServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    public function dashboard(ForecastServices $forecast)
    {
        $user = Auth::user();

        $forecast7 = $forecast->forecastRevenueSeries(60, 7, 0.5);
        $forecast30 = $forecast->forecastRevenueSeries(60, 30, 0.5);

        $todaySales   = Order::whereDate('created_at', today())->sum('total_amount');
        $weekSales    = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');
        $monthSales   = Order::whereMonth('created_at', now()->month)->sum('total_amount');
        $grossRevenue = Order::sum('total_amount');
        $averageTransaction = Order::avg('total_amount');

        $topProducts = OrderItems::selectRaw('product_id, SUM(quantity) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('product')
            ->get();

        $paymentMethods = Order::selectRaw('payment_method, COUNT(*) as total')
            ->groupBy('payment_method')
            ->get();

        $recentTransactions = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $lowStock = ProductPrices::with('product')
            ->where('quantity_stock', '<=', 10)
            ->where('quantity_stock', '>', 0)
            ->orderBy('quantity_stock', 'asc')
            ->get();

        $outOfStock = ProductPrices::with('product')
            ->where('quantity_stock', 0)
            ->get();

        $salesTrends = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartDates  = $salesTrends->pluck('date')->map(fn($d) => Carbon::parse($d)->format('D'))->toArray();
        $chartTotals = $salesTrends->pluck('total')->toArray();

        $overallForecast = $forecast->forecastRevenueSeries(historyDays: 60, horizon: 7, alpha: 0.5);


        $forecastLabels = $overallForecast['dates'];
        $forecastData   = $overallForecast['values'];

        $forecastedStockOuts = $forecast->predictStockOuts(
            30,  
            0.25, 
            60,  
            7      
        );
        $forecastedStockOutCount = count($forecastedStockOuts);

        $employeeSales = User::where('role', 'cashier')
            ->withSum(['orders as total_sales' => function ($q) {
                $q->selectRaw('COALESCE(SUM(total_amount), 0)');
            }], 'total_amount')
            ->get();

        $employeeNames  = $employeeSales->pluck('name')->toArray();
        $employeeTotals = $employeeSales->pluck('total_sales')->toArray();
        $topSeller      = $employeeSales->sortByDesc('total_sales')->first();

        return view('owner.index', [
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

    public function activityLog()
    {
        $inventoryLogs = InventoryLogs::with(['product', 'user'])
            ->latest()
            ->get()
            ->map(function ($log) {
                $note = strtolower($log->note);
                if (str_contains($note, 'reorder') || str_contains($note, 'manual') || str_contains($note, 'stock adjustment')) {
                    $log->log_type = 'setting';
                } else {
                    $log->log_type = 'inventory';
                }
                return $log;
            });

        $orderLogs = Order::with(['user', 'items.product'])
            ->latest()
            ->get()
            ->map(function ($order) {
                $order->log_type = 'sale';
                return $order;
            });

        // Merge and sort
        $activities = $inventoryLogs->concat($orderLogs)
            ->sortByDesc('created_at')
            ->values();

        return view('owner.activity-log', compact('activities'));
    }
}
