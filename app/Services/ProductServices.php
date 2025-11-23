<?php


namespace App\Services;

use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\InventoryLogs;
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

        foreach ($data['sizes'] as $size) {
            $quantity = $size['quantity'] === '' ? null : (float) $size['quantity'];

            $pricesData = [
                'product_id' => $product->id,
                'price' => $size['price'] === '' ? null : (float) $size['price'],
                'reorder_level' => $size['reorder_level'] === '' ? null : (float) $size['reorder_level'],
                'quantity_stock' => $size['quantity'] === '' ? null : (float) $size['quantity'],
                'size' => $size['name'],
            ];

            ProductPrices::create($pricesData);

            if ($quantity && $quantity > 0) {
                InventoryLogs::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $quantity,
                    'note' => "Initial stock for size {$size['name']}",
                ]);
            }
        }

        return $product;
    }

    public function update(Product $product, array $data)
    {
        $product_image = null;
        if (isset($data['product_image']) && $data['product_image'] instanceof TemporaryUploadedFile) {
            $product_image = $this->handleImage($data['product_image'], $product->product_image);
        }

        $product->update([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'product_image' => $product_image ?? $product->product_image,
        ]);

        $submittedSizes = collect($data['prices'])->pluck('size')->toArray();
        $deletedPrices = $product->prices()->whereNotIn('size', $submittedSizes)->get();

        foreach ($deletedPrices as $price) {
            InventoryLogs::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $price->quantity_stock,
                'note' => "Size {$price->size} removed",
            ]);
        }

        $product->prices()->whereNotIn('size', $submittedSizes)->delete();

        foreach ($data['prices'] as $priceData) {

            $price = $product->prices()->where('size', $priceData['size'])->first();
            $oldQty = $price?->quantity_stock ?? 0;
            $newQty = $priceData['quantity_stock'] ?? 0;

            // Update or create
            $price = $product->prices()->updateOrCreate(
                ['size' => $priceData['size']],
                [
                    'price' => $priceData['price'] ?? null,
                    'quantity_stock' => $newQty,
                    'reorder_level' => $priceData['reorder_level'] ?? null,
                ]
            );

            if ($newQty != $oldQty) {
                InventoryLogs::create([
                    'product_id' => $product->id,
                    'type' => $newQty > $oldQty ? 'in' : 'out',
                    'quantity' => abs($newQty - $oldQty),
                    'note' => $oldQty == 0
                        ? "Initial stock for size {$price->size}"
                        : "Stock updated for size {$price->size}",
                ]);
            }
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