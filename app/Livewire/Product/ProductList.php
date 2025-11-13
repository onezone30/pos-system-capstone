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

    public string $search = "";

    #[On('searchUpdated')]
    public function productSearch($search)
    {
        $this->search = trim($search);
        $this->resetPage();
    }

    #[On('editProduct', 'createProduct')]
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function filteredProduct()
    {
          return Product::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('category', function($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->with('category')
            ->paginate(10);
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
        $categories = Category::query()
            ->get();

        return view('livewire.product.product-list', [
            'products' => $this->filteredProduct(),
            'categories' => $categories
        ]);
    }
}
