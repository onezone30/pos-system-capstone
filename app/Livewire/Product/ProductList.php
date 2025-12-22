<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\InventoryLogs; 
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    #[On(['editProduct', 'createProduct'])]
    public function refreshPage()
    {
        $this->resetPage();
    }

    public function delete(int $id)
    {
        $product = Product::with('prices')->findOrFail($id);
        
        foreach ($product->prices as $price) {
            if ($price->quantity_stock > 0) {
                InventoryLogs::create([
                    'product_id' => $product->id,
                    'user_id'    => auth()->id(),
                    'type'       => 'out',
                    'quantity'   => $price->quantity_stock,
                    'note'       => "Product Deleted: Size '{$price->size}' stock removed from system."
                ]);
            }
        }

        InventoryLogs::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'type'       => 'adjustment',
            'quantity'   => 0,
            'note'       => "Product '{$product->name}' was permanently deleted from the catalog."
        ]);

        if (! $product->delete()) {
            $this->dispatch('toast.error', message: "Failed to delete {$product->name}");
            return;
        }

        $this->refreshPage(); 
        $this->dispatch('toast.success', message: "{$product->name} and its inventory logs have been processed.");
        $this->dispatch('close-delete-modal');
    }

    public function render()
    { 
        $products = Product::with(['category', 'prices'])
            ->orderBy('id', 'desc') 
            ->paginate(10); 
            
        $categories = Category::query()->get();

        return view('livewire.product.product-list', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}