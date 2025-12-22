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
        return [
            'name'                => 'required|string|max:255',
            'category_id'         => 'required|exists:categories,id',
            'sizes'               => 'required|array|min:1',
            'sizes.*.name'        => 'required|string|max:100',
            'sizes.*.price'       => 'required|numeric|min:0|max:999999.99',
            'sizes.*.quantity'    => 'required|integer|min:0',
            'sizes.*.reorder_level' => 'required|integer|min:0',
            'product_image'       => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
        ];
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
        if (count($this->sizes) > 1) {
            unset($this->sizes[$index]);
            $this->sizes = array_values($this->sizes);
        } else {
            $this->dispatch('toast.error', message: 'At least one size is required.');
        }
    }

    public function create(ProductServices $service)
    {
        $validate = $this->validate();

        $productData = [
            'name'          => $validate['name'],
            'category_id'   => $validate['category_id'],
            'product_image' => $this->product_image,
            'sizes'         => $validate['sizes']
        ];

        if(!$service->create($productData)) {
            $this->dispatch('toast.error', message: 'Failed to create product');
            return;
        }

        $this->dispatch('toast.success', message: "{$productData['name']} has been created and initial stock logged.");

        $this->dispatch('createProduct');
        $this->resetForm();
        $this->dispatch('close-create-modal');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->category_id = 0;
        $this->product_image = null;
        $this->sizes = [
            ['name' => '', 'price' => '', 'quantity' => '', 'reorder_level' => '']
        ];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.product.create-product-form', [
            'categories' => Category::all(),
        ]);
    }
}