<?php

namespace App\Services;

use App\Models\InventoryLogs;
use App\Models\Product;
use App\Models\ProductPrices;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\DB;

class ProductServices {

    private function handleImage(?TemporaryUploadedFile $file, ?string $oldPath = null) 
    {
        if(!$file) return $oldPath;

        $imageName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $imagePath = $file->storeAs('images/products', $imageName, 'public');

        if($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $imagePath;
    }

    public function create(array $data) 
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create([
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'product_image' => $this->handleImage($data['product_image'] ?? null)
            ]);

            foreach ($data['sizes'] as $size) {
                $quantity = $size['quantity'] === '' ? 0 : (float) $size['quantity'];

                ProductPrices::create([
                    'product_id' => $product->id,
                    'price' => $size['price'] === '' ? null : (float) $size['price'],
                    'reorder_level' => $size['reorder_level'] === '' ? null : (float) $size['reorder_level'],
                    'quantity_stock' => $quantity,
                    'size' => $size['name'],
                ]);

                InventoryLogs::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'in',
                    'quantity' => $quantity,
                    'note' => "Product Created: Initial stock for size {$size['name']}",
                ]);
            }

            return $product;
        });
    }

    public function update(Product $product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {
            $product_image = $product->product_image;
            if (isset($data['product_image']) && $data['product_image'] instanceof TemporaryUploadedFile) {
                $product_image = $this->handleImage($data['product_image'], $product->product_image);
            }

            $product->update([
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'product_image' => $product_image,
            ]);

            $submittedSizes = collect($data['prices'])->pluck('size')->toArray();
            
            $deletedPrices = $product->prices()->whereNotIn('size', $submittedSizes)->get();
            foreach ($deletedPrices as $price) {
                InventoryLogs::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'out',
                    'quantity' => $price->quantity_stock,
                    'note' => "Manual Adjustment: Size '{$price->size}' removed from catalog. Stock cleared.",
                ]);
                $price->delete();
            }

            foreach ($data['prices'] as $priceData) {
                $priceRecord = $product->prices()->where('size', $priceData['size'])->first();
                
                $oldQty = $priceRecord?->quantity_stock ?? 0;
                $newQty = (int)($priceData['quantity_stock'] ?? 0);
                $oldReorder = $priceRecord?->reorder_level ?? 0;
                $newReorder = (int)($priceData['reorder_level'] ?? 0);
                $oldPrice = $priceRecord?->price ?? 0;
                $newPrice = (float)($priceData['price'] ?? 0);

                // Perform the Update or Create
                $updatedPrice = $product->prices()->updateOrCreate(
                    ['size' => $priceData['size']],
                    [
                        'price' => $newPrice,
                        'quantity_stock' => $newQty,
                        'reorder_level' => $newReorder,
                    ]
                );


                if (!$priceRecord) {
                    InventoryLogs::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'in',
                        'quantity' => $newQty,
                        'note' => "Manual Adjustment: New size '{$priceData['size']}' added with {$newQty} initial stock.",
                    ]);
                } 
                elseif ($newQty != $oldQty) {
                    InventoryLogs::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => $newQty > $oldQty ? 'in' : 'out',
                        'quantity' => abs($newQty - $oldQty),
                        'note' => "Stock Adjustment ({$priceData['size']}): {$oldQty} -> {$newQty}",
                    ]);
                }

                if ($priceRecord && $newReorder != $oldReorder) {
                    InventoryLogs::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'adjustment',
                        'quantity' => 0,
                        'note' => "Setting Change: Reorder level for {$priceData['size']} updated ({$oldReorder} -> {$newReorder})",
                    ]);
                }

                if ($priceRecord && $newPrice != $oldPrice) {
                    InventoryLogs::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'adjustment',
                        'quantity' => 0,
                        'note' => "Price Update ({$priceData['size']}): {$oldPrice} -> {$newPrice}",
                    ]);
                }
            }

            return $product;
        });
    }
}