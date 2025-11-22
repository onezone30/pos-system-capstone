<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductPrices;
use App\Models\SalesHistory;
use Illuminate\Support\Facades\DB;

class OrderServices {

    public function update(Order $order, array $data)
    {
        DB::transaction(function () use ($order, $data) {

            // --- UPDATE ORDER ---
            $change = $data['amount_paid'] - $data['total'];

            $order->update([
                'total_amount'    => $data['total'],
                'amount_paid'     => $data['amount_paid'],
                'change'          => $change,
                'payment_method'  => $data['payment_method'],
            ]);


            // --- DELETE REMOVED ITEMS ---
            $existingIds = $order->items()->pluck('id')->toArray();
            $incomingIds = array_filter(array_column($data['items'], 'id'));
            $toDelete = array_diff($existingIds, $incomingIds);

            if (!empty($toDelete)) {
                $order->items()->whereIn('id', $toDelete)->delete();
            }


            // --- PROCESS EACH ITEM ---
            foreach ($data['items'] as $item) {

                $orderItem = null;

                // UPDATE EXISTING ITEM
                if (!empty($item['id']) && in_array($item['id'], $existingIds)) {

                    $orderItem = $order->items()->where('id', $item['id'])->first();

                    // adjust stock correctly
                    $this->adjustStock($orderItem, $item);

                    // update
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
                        'price_id' => $item['price_id'],
                        'size'       => $item['size'] ?? null,
                        'price'      => $item['price'],
                        'quantity'   => $item['quantity'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    // adjust stock for new item
                    $this->createStockAndHistory($orderItem);
                }
            }
        });

        return $order->load('items.product');
    }


    // -------------------------
    // STOCK + SALES HISTORY LOGIC
    // -------------------------

    private function createStockAndHistory($orderItem)
    {
                // Debugger dump
        dd([
            'order_item_id'  => $orderItem->id,
            'product_id'     => $orderItem->product_id,
            'price_id'       => $orderItem->price_id,
            'size'           => $orderItem->size,
            'quantity'       => $orderItem->quantity,
            'price_per_item' => $orderItem->price,
            'subtotal'       => $orderItem->subtotal,
            'sales_history_data' => [
                'product_id'    => $orderItem->product_id,
                'order_item_id' => $orderItem->id,
                'date'          => now()->toDateString(),
                'quantity_sold' => $orderItem->quantity,
                'total_sales'   => $orderItem->quantity * $orderItem->price,
            ]
        ]);
        
        $price = ProductPrices::where('product_id', $orderItem->product_id)
            ->where('size', $orderItem->size)
            ->first();

        if (!$price) {
            throw new \Exception("Product price not found for product_id {$orderItem->product_id}");
        }

        $price->decrement('quantity_stock', $orderItem->quantity);



        SalesHistory::create([
            'order_id' => $orderItem->order_id,
            'product_id'     => $orderItem->product_id,
            'date'           => now()->toDateString(),
            'quantity_sold'  => $orderItem->quantity,
            'total_sales'    => $orderItem->quantity * $orderItem->price,
        ]);
    }


    private function adjustStock($orderItem, $newData)
    {
        $price = ProductPrices::find($orderItem->price_id);

        if (!$price) {
            throw new \Exception("Product price not found for product_id {$orderItem->product_id}");
        }

        // old quantity vs new quantity
        $difference = $newData['quantity'] - $orderItem->quantity;

        if ($difference === 0) return;

        if ($difference > 0) {
            $price->decrement('quantity_stock', $difference);
        } else {
            $price->increment('quantity_stock', abs($difference));
        }

        // Log history only for additional sales
        if ($difference > 0) {
            SalesHistory::create([
                'product_id'     => $orderItem->product_id,
                'order_item_id'  => $orderItem->id,
                'date'           => now()->toDateString(),
                'quantity_sold'  => $difference,
                'total_sales'    => $difference * $orderItem->price,
            ]);
        }
    }
}
