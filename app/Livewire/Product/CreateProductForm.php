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

    public array $sizes = [
        ['name' => '', 'price' => '', 'quantity' => '', 'reorder_level' => '']
    ];

    public function rules() 
    {
        $rules = [
            'name'                  => 'required|string|max:255',
            'category_id'           => 'required|exists:categories,id',
            'sizes'                 => 'required|array|min:1',
            'sizes.*.name'          => 'required|string|max:100',
            'sizes.*.price'         => 'required|numeric|min:0|max:999999.99',
            'sizes.*.quantity'      => 'required|integer|min:0',
            'sizes.*.reorder_level'      => 'required|integer|min:0',
            'product_image'         => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'sizes.*.name.required' => 'Each size needs a name.',
            'sizes.*.price.required' => 'Each size needs a price.',
            'sizes.*.quantity.required' => 'Each size needs a quantity.',
            'sizes.*.reorder_level.required' => 'Each size needs a reorder level.',
        ];
    }

    public function addSize()
    {
        $this->sizes[] = ['name' => '', 'price' => '', 'quantity' => '', 'reorder_level' => ''];
    }

    public function removeSize($index)
    {
        unset($this->sizes[$index]);
        $this->sizes = array_values($this->sizes);
    }

    public function create(ProductServices $service)
    {
        $validate = $this->validate();

        $productData = [
            'name'          => $validate['name'],
            'category_id'   => $validate['category_id'],
            'product_image' => $validate['product_image'],
            'sizes'         => $validate['sizes']
        ];

        
        if(!$service->create($productData)) {
            $this->dispatch('toast.error', message: 'Failed to create product');
            return;
        }

        $this->dispatch('toast.success', message: "{$productData['name']} has been created");

        $this->dispatch( 'createProduct');
        $this->resetForm();
        $this->dispatch('close-create-modal');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->category_id = 0;
        $this->sizes = [['name' => '', 'price' => '', 'quantity' => '']];
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
