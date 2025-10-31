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
            $price = trim((string)($data['prices'][$index] ?? ''));
            $quantity = trim((string)($data['quantities'][$index] ?? ''));

            $pricesData = [
                'product_id' => $product->id,
                'price' => $price === '' ? null : (float) $price,
                'quantity_stock' => $quantity === '' ? null : (float) $quantity,
                'size' => $size,
            ];

            ProductPrices::create($pricesData);
        }

        return $product;
    }

    public function update(Product $product, array $data) {

        if(
            isset($data['product_image']) &&
            $data['product_image'] instanceof TemporaryUploadedFile
            ) {
                $product_image = $this->handleImage($data['product_image'], $product->product_image);
            }
        else  {
                $product_image = null;
            }


        $productData = [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'product_image' => $product_image,
        ];
        
        $product->update($productData);

        foreach($data['prices'] as $priceData) {
            $product->prices()->updateOrCreate(
                ['size' => $priceData['size']],
                [
                    'price'          => $priceData['price'] ?? null,
                    'quantity_stock' => $priceData['quantity_stock'] ?? null,
                ]
            );
        }

        return $product;
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