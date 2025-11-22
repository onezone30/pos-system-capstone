<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\ProductPrices;
use App\Models\SalesHistory;
use App\Models\InventoryLogs;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = ProductPrices::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('No users or products found. Seed users/products first.');
            return;
        }

        // Create 50 random orders
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0,
                'amount_paid' => 0,
                'customer_name' => 'Customer ' . Str::random(5),
                'change' => 0,
                'payment_method' => Order::PAYMENT_METHOD[array_rand(Order::PAYMENT_METHOD)],
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now()
            ]);

            // Pick 1–5 random product sizes for this order
            $orderProducts = $products->random(rand(1, 5));
            $total = 0;

            foreach ($orderProducts as $price) {

                if ($price->quantity_stock <= 0) {
                    continue;
                }

                $quantity = rand(1, 5);
                $subtotal = $price->price * $quantity;

                if ($quantity > $price->quantity_stock) {
                    continue;
                }

                // Create order item
                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $price->product_id,
                    'price_id' => $price->id,
                    'quantity' => $quantity,
                    'price' => $price->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;

                // Create sales history
                SalesHistory::create([
                    'order_id' => $order->id,
                    'product_id' => $price->product_id,
                    'date' => $order->created_at->toDateString(),
                    'quantity_sold' => $quantity,
                    'total_sales' => $subtotal
                ]);


                // Deduct stock
                $price->decrement('quantity_stock', $quantity);
            }

            // Update order totals
            $order->update([
                'total_amount' => $total,
                'amount_paid' => $total,
                'change' => 0
            ]);
        }

        $this->command->info('50 orders created with items, sales history, and inventory logs.');
    }
}
