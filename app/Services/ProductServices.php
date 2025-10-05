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

    private function updateProfile(?TemporaryUploadedFile $file, ?string $oldPath = null)
    {
        if($file) {

            $imageName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $imagePath = $file->storeAs('images/products', $imageName, 'public');

            if($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        return $imagePath;

    }

    public function create(array $data) 
    {
        $product = Product::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'product_image' => $data['product_image'] /* $this->updateProfile($data['product_image']) ?? null */
        ]);

        foreach ($data['sizes'] as $index => $size) {
            ProductPrices::create([
                'product_id' => $product->id,
                'price' => $data['prices'][$index] ?? null,
                'quantity_stock' => $data['quantities'][$index] ?? null,
                'size' => $size,
            ]);
        }
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
                'product_image' => $this->updateProfile($productData['product_image']) ?? $product->proudct_image
            ]
        );

        $this->syncPrices($product, $priceData);

        return $product;

    }


}