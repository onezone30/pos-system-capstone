<?php


namespace App\Services;

use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductPrices;

class ProductServices {

    private function syncPrices(Product $product, array $priceRequest) {

        $sizes = $priceRequest['size'] ?? [];
        $prices = $priceRequest['price'] ?? [];
        $quantities = $priceRequest['quantity_stock'] ?? [];
        
        foreach($sizes as $index => $size){

            $price_id = $product->prices[$index]->id ?? null;

            $product->prices()->updateOrCreate([
                'id' => $price_id],
                [
                    'product_id' => $product->id,
                    'price' => $prices[$index] ?? null,
                    'quantity_stock' => $quantities[$index] ?? null,
                    'size' => $size ?? null
                ]
            );
        }
    }


    public function saveProduct(?Product $product, ProductRequest $productRequest, PriceRequest $priceRequest) {

        $productData = $productRequest->validated();
        $priceData = $priceRequest->validated();

        $product = Product::updateOrCreate(
            ['id' => $product?->id],
            [
                'name' => $productData['name'],
                'category_id' => $productData['category']
            ]
        );

        $this->syncPrices($product, $priceData);

        return $product;

    }


}