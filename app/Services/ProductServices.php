<?php


namespace App\Services;

use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductServices {

    private function handleImage(?TemporaryUploadedFile $file, ?string $oldPath = null) 
    {
        if(!$file) {
            return $oldPath;
        }

        $imageName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $imagePath = $file->storeAs('images/products', $imageName, 'public');

        if($oldPath && $oldPath !== $imagePath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $imagePath;
    }

    public function create(array $data) 
    {
        $productData = [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'product_image' => $this->handleImage($data['product_image'] ?? null)
        ];


        $product = Product::create($productData);

        foreach ($data['sizes'] as $index => $size) {
            $pricesData = [
                'product_id' => $product->id,
                'price' => $data['prices'][$index] ?? null,
                'quantity_stock' => $data['quantities'][$index] ?? null,
                'size' => $size,
            ];

            ProductPrices::create($pricesData);
        }

        return $product;
    }

    public function update(Product $product, array $data) {

        $productData = [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'product_image' => $data['product_image'],
        ];

        foreach($data['sizes'] as $index => $size) {
            $pricesData = [
                'product_id' => $productData['id'],
                'price' => $data['prices'][$index] ?? null,
                'quantity_stock' => $data['quantities'][$index] ?? null,
                'size' => $size,
            ];
            $product->prices->update($pricesData);
        }

        return $product->update($productData);
    }

    private function syncPrices(object $product, array $priceRequest) {

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


    public function saveProduct(?object $product, array $productData, array $priceData) {

        $product = Product::updateOrCreate(
            ['id' => $product?->id],
            [
                'name' => $productData['name'],
                'category_id' => $productData['category_id'],
                'product_image' => $this->handleImage($productData['product_image']) ?? $product->proudct_image
            ]
        );

        $this->syncPrices($product, $priceData);

        return $product;

    }


}