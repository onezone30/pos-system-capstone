<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Services\ProductServices;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProductForm extends Component
{
    use WithFileUploads;

    public Product $product;

    public string $name;
    public int $category_id;
    public $product_image;

    public array $sizes = ['small', 'medium', 'large'];
    public array $prices = [];
    public array $quantities = [];

    public function rules()
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'prices'        => 'array',
            'quantities'    => 'array',
            'prices.*'      => 'required_with:quantities.*|numeric|min:0|max:999999.99',
            'quantities.*'  => 'required_with:prices.*|integer|min:0',
        ];

        if ($this->product_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $rules['product_image'] = 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp';
        }

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

    #[On('open-edit-modal')]
    public function load($id)
    {
        $this->product = Product::with('prices')->findOrFail($id);
        $this->name = $this->product->name;
        $this->category_id = $this->product->category_id;
        $this->product_image = $this->product->product_image;

        foreach($this->sizes as $index => $size) {
            $price = $this->product->prices->where('size', $size)->first();
            $this->prices[$index] = $price?->price ?? '';
            $this->quantities[$index] = $price?->quantity_stock ?? '';
        }
    }

    public function removeProductImage()
    {
        if($this->product->product_image) {
            Storage::delete('public/' . $this->product->product_image);
        }

        $this->product_image = null;
        $this->dispatch('toast.success', message: 'Product image removed');
    }

    public function update(ProductServices $service)
    {
        $validated = $this->validate();

        $data = [
            'name'          => $validated['name'],
            'category_id'   => $validated['category_id'],
            'product_image' => $this->product_image,
            'prices' => collect($this->sizes)->map(function ($size, $index) {
                return [
                    'size'           => $size,
                    'quantity_stock' => $this->quantities[$index] === '' ? null : $this->quantities[$index],
                    'price'          => $this->prices[$index] === '' ? null : $this->prices[$index],
                ];
            })->toArray(),
        ];

        $service->update($this->product, $data);

        $this->dispatch('toast.success', message: "{$data['name']} has been updated");
        $this->dispatch('editProduct');
        $this->dispatch('close-edit-modal');
    }


    public function render()
    {
        $categories = Category::all();

        return view('livewire.product.edit-product-form', [
            'categories' => $categories,
        ]);
    }
}
