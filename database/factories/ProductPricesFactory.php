<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPrices>
 */
class ProductPricesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $size = fake()->randomElement(['small', 'medium', 'large']);
        
        return [
            'product_id' => Product::factory(),
            'size' => $size,
            'price' => match ($size) {
                'small' => fake()->numberBetween(25, 35),
                'medium' => fake()->numberBetween(35, 45),
                'large' => fake()->numberBetween(45, 55),
            },
            'quantity_stock' => fake()->numberBetween(20, 50)
        ];
    }

    /**
     * Create all sizes for a specific product
     */
    public function allSizesForProduct(Product $product): array
    {
        $sizes = ['small', 'medium', 'large'];
        $prices = [];

        foreach ($sizes as $size) {
            $prices[] = [
                'product_id' => $product->id,
                'size' => $size,
                'price' => match ($size) {
                    'small' => fake()->numberBetween(25, 35),
                    'medium' => fake()->numberBetween(35, 45),
                    'large' => fake()->numberBetween(45, 55),
                },
                'quantity_stock' => fake()->numberBetween(20, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $prices;
    }

    /**
     * Create instances for all sizes
     */
    public function allSizes()
    {
        return $this->sequence(
            ['size' => 'small', 'price' => fake()->numberBetween(25, 35)],
            ['size' => 'medium', 'price' => fake()->numberBetween(35, 45)],
            ['size' => 'large', 'price' => fake()->numberBetween(45, 55)]
        );
    }

    /**
     * Create all sizes for a specific product (alternative method)
     */
    public static function createAllSizesFor(Product $product)
    {
        $sizes = ['small', 'medium', 'large'];
        
        foreach ($sizes as $size) {
            static::new()->create([
                'product_id' => $product->id,
                'size' => $size,
                'price' => match ($size) {
                    'small' => fake()->numberBetween(25, 35),
                    'medium' => fake()->numberBetween(35, 45),
                    'large' => fake()->numberBetween(45, 55),
                },
                'quantity_stock' => fake()->numberBetween(20, 50)
            ]);
        }
    }
}