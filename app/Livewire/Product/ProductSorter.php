<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Livewire\ProductOrderList;
use Livewire\Component;

class ProductSorter extends Component
{
    public $category = '';
    public $field = 'name';
    public $order = 'asc';

    public function sort()
    {
        $this->dispatch('sortProduct', [
            'category' => $this->category,
            'order' => $this->order,
            'field' => $this->field,
        ])->to(ProductOrderList::class);
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.product.product-sorter', [
            'categories' => $categories,
        ]);
    }
}
