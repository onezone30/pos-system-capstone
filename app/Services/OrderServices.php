<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderServices {

    public function update(Order $order, array $data)
    {
        DB::transaction(function () use ($order, $data) {
            $change = $data['amount_paid'] - $data['total'];

            $order->update([
                'total_amount' => $data['total'],
                'amount_paid' => $data['amount_paid'],
                'change' => $change,
                'payment_method' => $data['payment_method'],
            ]);

            $existingIds = $order->items()->pluck('id')->toArray();
            $incomingIds = array_filter(array_column($data['items'], 'id'));

            $toDelete = array_diff($existingIds, $incomingIds);
            if (!empty($toDelete)) {
                $order->items()->whereIn('id', $toDelete)->delete();
            }

            foreach ($data['items'] as $item) {
                if (!empty($item['id']) && in_array($item['id'], $existingIds)) {
                    $order->items()->where('id', $item['id'])->update([
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);
                } else {
                    $order->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }
            }
        });

        return $order->load('items.product');
    }


    public function deleteCategory(object $category)
    {
        return $category->delete();
    }


}