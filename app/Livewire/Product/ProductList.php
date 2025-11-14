<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;


    #[On('editProduct', 'createProduct')]
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(int $id)
    {
        $product = Product::findOrFail($id);
        
        if(! $product->delete()) {
            $this->dispatch('toast.success', message: "Failed to delete {$product->name}");
        }

        $this->updatingSearch();
        $this->dispatch('toast.success', message: "{$product->name} has been deleted");
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {            
        $products = Product::with('category')->get();
        $categories = Category::query()
            ->get();

        return view('livewire.product.product-list', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
