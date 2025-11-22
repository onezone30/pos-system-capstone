<?php

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class, 'order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class);
            $table->date('date');
            $table->integer('quantity_sold')->default(0);
            $table->decimal('total_sales', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_histories');
    }
};
