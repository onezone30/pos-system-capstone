<?php

namespace App\Livewire\Product;

use App\Models\Category;
use Livewire\WithFileUploads;
use App\Services\ProductServices;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProductForm extends Component
{
    use WithFileUploads;

    public string $name = '';
    public int $category_id = 0;
     #[Validate('nullable|image|max:2048')]
    public $product_image;

    public array $sizes = ['small', 'medium', 'large'];
    public array $prices = [];
    public array $quantities = [];

    public function rules() 
    {
        return [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',

            'prices'        => 'array',
            'quantities'    => 'array',

            'prices.*'      => 'required_with:quantities.*|numeric|min:0',

            'quantities.*'  => 'required_with:prices.*|integer|min:0',
            'product_image' => 'nullable|image|max:2048',
        ];
    }


    public function create(ProductServices $productServices)
    {
        $this->validate();

        $productData = [
            'name'          => $this->name,
            'category_id'   => $this->category_id,
            'product_image' => $this->product_image,
            'sizes'         => $this->sizes,
            'prices'        => $this->prices,
            'quantities'    => $this->quantities
        ];

        $productServices->create($productData);

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
