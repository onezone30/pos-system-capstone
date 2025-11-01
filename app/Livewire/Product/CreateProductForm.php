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
    public array $prices = ['', '', ''];
    public array $quantities = ['', '', ''];

    public function rules() 
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'prices'        => 'array',
            'quantities'    => 'array',
            'prices.*'      => 'required_with:quantities.*|min:0|max:999999.99',
            'quantities.*'  => 'required_with:prices.*|integer|min:0',
            'product_image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'             => 'Product name is required.',
            'category_id.required'      => 'Please select a category.',
            'category_id.exists'        => 'The selected category is invalid.',
            'prices.*.required_with'    => 'Price is required when quantity is provided.',
            'prices.*.numeric'          => 'Price must be a valid number.',
            'prices.*.min'              => 'Price cannot be negative.',
            'prices.*.max'              => 'Price cannot exceed 999,999.99.',
            'quantities.*.required_with'=> 'Quantity is required when price is provided.',
            'quantities.*.integer'      => 'Quantity must be an integer.',
            'quantities.*.min'          => 'Quantity cannot be negative.',
            'product_image.image'       => 'Uploaded file must be an image.',
            'product_image.max'         => 'Image size must not exceed 2MB.',
            'product_image.mimes'       => 'Only JPG, JPEG, PNG, and WEBP formats are allowed.',
        ];
    }


    public function create(ProductServices $service)
    {
        $validate = $this->validate();

        $productData = [
            'name'          => $validate['name'],
            'category_id'   => $validate['category_id'],
            'product_image' => $validate['product_image'],
            'sizes'         => $this->sizes,
            'prices'        => $validate['prices'],
            'quantities'    => $validate['quantities']
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
