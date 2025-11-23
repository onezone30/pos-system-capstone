<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\Price; // The variant / stock table
use App\Models\ProductPrices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class EditInventoryForm extends Component
{

    public $product;
    public $price;

    public $reorder_level;
    public $quantity_stock;


    #[On('open-edit-modal')]
    public function loadItem($price_id)
    {

        $this->price = ProductPrices::findOrFail($price_id);

        $this->reorder_level = $this->price->first()->reorder_level;
        $this->quantity_stock = $this->price->first()->quantity_stock;
        
    }

    public function rules()
    {
        return [
            'reorder_level'  => ['required', 'integer', 'min:0'],
            'quantity_stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function update()
    {
        $this->validate();

        $this->price->update([
            'quantity_stock' => $this->quantity_stock,
            'reorder_level' => $this->reorder_level,
        ]);

        $this->dispatch('editInventory'); 
        $this->dispatch('close-edit-modal');
        $this->dispatch('toast.success', 'Inventory updated successfully!');
    }

    public function render()
    {
        return view('livewire.inventory.edit-inventory-form');
    }
}
