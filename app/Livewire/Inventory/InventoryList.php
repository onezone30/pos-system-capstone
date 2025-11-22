<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryList extends Component
{
    use WithPagination;


    #[On('editInventory')]
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::with('category', 'prices')->paginate(10);

        return view('livewire.inventory.inventory-list', [
            'products' => $products,
        ]);
    }
}
