<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPrices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        Product::factory(10)->create()->each(function ($product) {
            ProductPrices::factory()->count(3)->allSizes()->create([
                'product_id' => $product->id
            ]);
        });
    }
}
