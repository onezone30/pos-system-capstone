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
    public array $reorder_levels = [];

    public function rules()
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'sizes'         => 'array|min:1',
            'sizes.*'       => 'required|string|max:255',
            'prices'        => 'array',
            'quantities'    => 'array',
            'reorder_levels'    => 'array',
            'prices.*'      => 'nullable|numeric|min:0|max:999999.99',
            'quantities.*'  => 'nullable|integer|min:0',
            'reorder_levels.*'  => 'nullable|integer|min:0',
        ];

        if ($this->product_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $rules['product_image'] = 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp';
        }

        return $rules;
    }

    #[On('open-edit-modal')]
    public function load($id)
    {
        
        $this->product = Product::with('prices')->findOrFail($id);
        $this->name = $this->product->name;
        $this->category_id = $this->product->category_id;
        $this->product_image = $this->product->product_image;

        // Load all existing sizes dynamically
        $this->sizes = [];
        $this->prices = [];
        $this->quantities = [];
        $this->reorder_levels = [];

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
        $this->prices[] = '';
        $this->quantities[] = '';
        $this->reorder_levels[] = '';
    }

    public function removeProductImage()
    {
        if($this->product->product_image) {
            Storage::delete('public/' . $this->product->product_image);
        }

        $this->product_image = null;
        $this->dispatch('toast.success', message: 'Product image removed');
    }

    public function removeSize($index)
    {
        unset($this->sizes[$index]);
        $this->sizes = array_values($this->sizes);
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
                    'reorder_level' => $this->reorder_levels[$index] === '' ? null : $this->reorder_levels[$index],
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
