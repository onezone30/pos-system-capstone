<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;


    public string $search = "";
    protected $listeners = [
        'createProduct' => 'updatingSearch',
        'editProduct' => 'updatingSearch',
        'searchUpdated' => 'productSearch'
    ];

    public function productSearch($search)
    {
        $this->search = trim($search);
        $this->load();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function filteredProduct()
    {
        return Product::query()
            ->when($this->search, function($query) {
                $search = "%{$this->search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhereHas('category', function ($cat) use ($search) {
                            $cat->where('name', 'like', $search);
                        });
                });
            })
            ->with('category')
            ->latest()
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
