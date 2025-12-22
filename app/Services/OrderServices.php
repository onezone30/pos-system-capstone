<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductPrices;
use App\Models\SalesHistory;
use App\Models\InventoryLogs;
use Illuminate\Support\Facades\DB;

class OrderServices {

    public function update(Order $order, array $data)
    {
        return DB::transaction(function () use ($order, $data) {

            // --- 1. UPDATE ORDER ---
            $change = $data['amount_paid'] - $data['total'];

            $order->update([
                'total_amount'    => $data['total'],
                'amount_paid'     => $data['amount_paid'],
                'change'          => $change,
                'payment_method'  => $data['payment_method'],
            ]);

            // --- 2. DELETE REMOVED ITEMS & RESTORE STOCK ---
            $existingIds = $order->items()->pluck('id')->toArray();
            $incomingIds = array_filter(array_column($data['items'], 'id'));
            $toDelete = array_diff($existingIds, $incomingIds);

            if (!empty($toDelete)) {
                $itemsToDelete = $order->items()->whereIn('id', $toDelete)->get();
                
                foreach ($itemsToDelete as $item) {
                    $this->restoreStockAndLog($item);
                    $item->delete();
                }
            }

            // --- 3. PROCESS EACH ITEM ---
            foreach ($data['items'] as $item) {
                // UPDATE EXISTING ITEM
                if (!empty($item['id']) && in_array($item['id'], $existingIds)) {
                    $orderItem = $order->items()->where('id', $item['id'])->first();

                    // adjust stock and log inventory
                    $this->adjustStock($orderItem, $item);

                    $orderItem->update([
                        'price'    => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);
                } 
                // CREATE NEW ITEM
                else {
                    $orderItem = $order->items()->create([
                        'product_id' => $item['product_id'],
                        'price_id'   => $item['price_id'],
                        'size'       => $item['size'] ?? null,
                        'price'      => $item['price'],
                        'quantity'   => $item['quantity'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    $this->createStockAndHistory($orderItem);
                }
            }
        });

        return $order->load('items.product');
    }

    private function createStockAndHistory($orderItem)
    {
        $price = ProductPrices::find($orderItem->price_id);

        if (!$price) {
            throw new \Exception("Product price not found for ID {$orderItem->price_id}");
        }

        // Decrement Stock
        $price->decrement('quantity_stock', $orderItem->quantity);

        // Log Inventory
        InventoryLogs::create([
            'product_id' => $orderItem->product_id,
            'user_id'    => auth()->id(),
            'type'       => 'out',
            'quantity'   => $orderItem->quantity,
            'note'       => "Order #{$orderItem->order_id}: New item added ({$orderItem->size})",
        ]);

        // Sales History
        SalesHistory::create([
            'order_id'      => $orderItem->order_id,
            'product_id'    => $orderItem->product_id,
            'date'          => now()->toDateString(),
            'quantity_sold' => $orderItem->quantity,
            'total_sales'   => $orderItem->quantity * $orderItem->price,
        ]);
    }

    private function adjustStock($orderItem, $newData)
    {
        $price = ProductPrices::find($orderItem->price_id);
        $difference = $newData['quantity'] - $orderItem->quantity;

        if ($difference === 0) return;

        if ($difference > 0) {
            // Sold more: Stock goes OUT
            $price->decrement('quantity_stock', $difference);
            $type = 'out';
            $note = "Order #{$orderItem->order_id}: Quantity increased for {$orderItem->size}";
            
            // Log additional sales history
            SalesHistory::create([
                'product_id'    => $orderItem->product_id,
                'order_id'      => $orderItem->order_id,
                'date'          => now()->toDateString(),
                'quantity_sold' => $difference,
                'total_sales'   => $difference * $newData['price'],
            ]);
        } else {
            // Reduced quantity: Stock comes back IN
            $absDiff = abs($difference);
            $price->increment('quantity_stock', $absDiff);
            $type = 'in';
            $note = "Order #{$orderItem->order_id}: Quantity decreased for {$orderItem->size}";
            
            // Optional: Log negative sales if you want to track returns in history
            SalesHistory::create([
                'product_id'    => $orderItem->product_id,
                'order_id'      => $orderItem->order_id,
                'date'          => now()->toDateString(),
                'quantity_sold' => $difference, // will be negative
                'total_sales'   => $difference * $newData['price'],
            ]);
        }

        InventoryLogs::create([
            'product_id' => $orderItem->product_id,
            'user_id'    => auth()->id(),
            'type'       => $type,
            'quantity'   => abs($difference),
            'note'       => $note,
        ]);
    }

    private function restoreStockAndLog($orderItem)
    {
        $price = ProductPrices::find($orderItem->price_id);
        
        if ($price) {
            $price->increment('quantity_stock', $orderItem->quantity);

            InventoryLogs::create([
                'product_id' => $orderItem->product_id,
                'user_id'    => auth()->id(),
                'type'       => 'in',
                'quantity'   => $orderItem->quantity,
                'note'       => "Order #{$orderItem->order_id}: Item removed ({$orderItem->size})",
            ]);

            // Log reversal in sales history
            SalesHistory::create([
                'product_id'    => $orderItem->product_id,
                'order_id'      => $orderItem->order_id,
                'date'          => now()->toDateString(),
                'quantity_sold' => -$orderItem->quantity,
                'total_sales'   => -($orderItem->quantity * $orderItem->price),
            ]);
        }
    }
}