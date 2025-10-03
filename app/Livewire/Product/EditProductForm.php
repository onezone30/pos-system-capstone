<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Services\ProductServices;
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

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'prices.*' => 'nullable|numeric|min:0',
        'quantities.*' => 'nullable|integer|min:0',
        'product_image' => 'nullable|image'
    ];

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

    public function update(ProductServices $services)
    {
        $validate = $this->validate();

        $productData = [
            'name'              => $validate['name'],
            'product_image'     => $validate['product_image'],
            'category_id'       => $validate['category_id'],
        ];

        $priceData = [];
        foreach ($this->sizes as $index => $size) {
            $priceData[] = [
                'size'           => $size,
                'quantity_stock' => $this->quantities[$index] === '' ? null : $this->quantities[$index] ,
                'price'          => $this->prices[$index] === '' ? null : $this->prices[$index],
            ];
        }

        $services->saveProduct($this->product, $productData, $priceData);

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
