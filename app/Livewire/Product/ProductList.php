<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductList extends Component
{
    protected $listeners = [
        'createProduct' => '$refresh',
        'deleteProduct' => '$refresh',
        'editProduct' => '$refresh'
    ];

    public function delete(int $id)
    {
        Product::findOrFail($id)->delete();

        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        $products = Product::with('prices', 'category')->latest()->get();
        $categories = Category::all();

        return view('livewire.product.product-list', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
