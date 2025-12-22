<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductServices;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProductForm extends Component
{
    use WithFileUploads;

    public Product $product;
    public $name;
    public $category_id;
    public $product_image;
    
    // Parallel arrays for dynamic sizes
    public array $sizes = [];
    public array $prices = [];
    public array $quantities = [];
    public array $reorder_levels = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sizes.*' => 'required|string|max:50',
            'prices.*' => 'required|numeric|min:0',
            'quantities.*' => 'required|integer|min:0',
            'reorder_levels.*' => 'required|integer|min:0',
            'product_image' => 'nullable|image|max:2048',
        ];
    }

    #[On('open-edit-modal')]
    public function load($id)
    {
        $this->product = Product::with('prices')->findOrFail($id);
        $this->name = $this->product->name;
        $this->category_id = $this->product->category_id;
        
        $this->sizes = []; $this->prices = []; $this->quantities = []; $this->reorder_levels = [];

        foreach ($this->product->prices as $price) {
            $this->sizes[] = $price->size;
            $this->prices[] = $price->price;
            $this->quantities[] = $price->quantity_stock;
            $this->reorder_levels[] = $price->reorder_level;
        }
    }

    public function addSize()
    {
        $this->sizes[] = '';
        $this->prices[] = 0;
        $this->quantities[] = 0;
        $this->reorder_levels[] = 5; 
    }

    public function removeSize($index)
    {
        unset($this->sizes[$index], $this->prices[$index], $this->quantities[$index], $this->reorder_levels[$index]);
        $this->sizes = array_values($this->sizes);
        $this->prices = array_values($this->prices);
        $this->quantities = array_values($this->quantities);
        $this->reorder_levels = array_values($this->reorder_levels);
    }

    public function update(ProductServices $service)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'product_image' => $this->product_image,
            'prices' => collect($this->sizes)->map(function ($size, $index) {
                return [
                    'size' => $size,
                    'price' => $this->prices[$index],
                    'quantity_stock' => $this->quantities[$index],
                    'reorder_level' => $this->reorder_levels[$index],
                ];
            })->toArray(),
        ];

        $service->update($this->product, $data);

        $this->dispatch('toast.success', 'Product and Inventory updated');
        $this->dispatch('close-edit-modal');
        $this->dispatch('editProduct'); 
    }

    public function render()
    {
        return view('livewire.product.edit-product-form', [
            'categories' => Category::all()
        ]);
    }
}