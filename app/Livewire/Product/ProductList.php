<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductList extends Component
{
    public $products = [];

    public string $search = "";
    protected $listeners = [
        'createProduct' => 'load',
        'editProduct' => 'load',
        'searchUpdated' => 'productSearch'
    ];

    public function mount()
    {
        $this->load();
    }

    public function load()
    {
        $this->products = $this->filteredProduct();
    }

    public function productSearch($search)
    {
        $this->search = trim($search);
        $this->load();
    }

    public function filteredProduct()
    {
        $products = Product::query()
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
            ->get();

        return $products;
    }

    public function delete(int $id)
    {
        $product = Product::findOrFail($id);
        
        if(! $product->delete()) {
            $this->dispatch('toast.success', message: "Failed to delete {$product->name}");
        }

        $this->load();
        $this->dispatch('toast.success', message: "{$product->name} has been deleted");
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {            
        $categories = Category::query()
            ->get();

        return view('livewire.product.product-list', [
            'products' => $this->products,
            'categories' => $categories
        ]);
    }
}
