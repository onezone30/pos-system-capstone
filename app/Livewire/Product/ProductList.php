<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductList extends Component
{
    public string $search = "";
    protected $listeners = [
        'createProduct' => '$refresh',
        'deleteProduct' => '$refresh',
        'editProduct' => '$refresh',
        'searchUpdated' => 'productSearch'
    ];

    public function productSearch($search)
    {
        $this->search = trim($search);
    }

    public function delete(int $id)
    {
        Product::findOrFail($id)->delete();

        $this->dispatch('success-delete');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('category', function($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->with('category')
            ->latest()
            ->get();
            
        $categories = Category::query()
            ->get();

        return view('livewire.product.product-list', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
