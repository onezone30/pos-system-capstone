<?php

namespace App\Livewire\Product;

use App\Models\Category;
use Livewire\WithFileUploads;
use App\Services\ProductServices;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateProductForm extends Component
{
    use WithFileUploads;

    public string $name = '';
    public int $category_id = 0;
    public $product_image = null;

    public array $sizes = ['small', 'medium', 'large'];
    public array $prices = [];
    public array $quantities = [];


    public function create(ProductServices $productServices)
    {
        $validated = $this->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',

            // Arrays must match your sizes length
            'prices'        => 'array',
            'quantities'    => 'array',

            // Each price: only validate numeric if it's not null
            'prices.*'      => 'required_with:quantities.*|numeric|min:0',

            // Each quantity: only validate integer if it's not null
            'quantities.*'  => 'required_with:prices.*|integer|min:0',

            'product_image' => 'nullable|image',
        ]);



        if($this->product_image) {
            $fileName = time() . '_' . str_replace(' ', '_', $this->product_image->getClientOriginalName());

            $path = $this->product_image->storeAs('images/products', $fileName, 'public');
        } else {
            $path = null;
        }


        $productServices->create([
            'name'          => $validated['name'],
            'category_id'   => $validated['category_id'],
            'product_image' => $path,
            'sizes'         => $this->sizes,
            'prices'        => $validated['prices'],
            'quantities'    => $validated['quantities']
        ]);


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
        $this->product_image = null;
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.product.create-product-form', [
            'categories' => $categories,
        ]);
    }
}
