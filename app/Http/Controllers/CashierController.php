<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $todaysSales = Order::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $todaysTransactions = Order::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $avgTransaction = Order::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->avg('total_amount');

        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        $paymentMethods = Order::selectRaw('payment_method, COUNT(*) as total')
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->groupBy('payment_method')
            ->get();

        $paymentBreakdown = Order::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return view('cashier.index', [
            'todaysSales' => $todaysSales,
            'todaysTransactions' => $todaysTransactions,
            'avgTransaction' => $avgTransaction,
            'recentOrders' => $recentOrders,
            'paymentBreakdown' => $paymentBreakdown,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}
