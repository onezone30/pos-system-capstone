<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryLogs;
use App\Models\Order;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
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

        return view('admin.activity-log', compact('activities'));
    }
}
