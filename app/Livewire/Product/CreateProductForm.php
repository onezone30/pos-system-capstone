<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrices;
use Livewire\Component;

class CreateProductForm extends Component
{
    public string $name = '';
    public int $category_id = 0;

    public array $sizes = ['small', 'medium', 'large'];
    public array $prices = [];
    public array $quantities = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'prices.*' => 'required|numeric|min:0',
        'quantities.*' => 'required|integer|min:0',
    ];

    public function create()
    {
        $this->validate();

        $product = Product::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
        ]);

        foreach($this->sizes as $index => $size) {
            ProductPrices::create([
                'product_id' => $product->id,
                'price' => $this->prices[$index] ?? 0,
                'quantity_stock' => $this->quantities[$index] ?? 0,
                'size' => $size
            ]);
        }

        $this->dispatch('createProduct');
        $this->resetForm();
        $this->dispatch('close-create-modal');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->category_id = 0;
        $this->prices = [];
        $this->quantities = [];
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.product.create-product-form', [
            'categories' => $categories,
        ]);
    }
}
