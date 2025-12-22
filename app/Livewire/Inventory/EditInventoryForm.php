<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\ProductPrices;
use App\Models\InventoryLogs;
use Livewire\Attributes\On;

class EditInventoryForm extends Component
{
    public $price;
    public $reorder_level;
    public $quantity_stock;

    #[On('open-edit-modal')]
    public function loadItem($price_id)
    {
        $this->price = ProductPrices::findOrFail($price_id);
        $this->reorder_level = $this->price->reorder_level;
        $this->quantity_stock = $this->price->quantity_stock;
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

        $oldStock = $this->price->quantity_stock;
        $oldReorder = $this->price->reorder_level;

        $this->price->update([
            'quantity_stock' => $this->quantity_stock,
            'reorder_level' => $this->reorder_level,
        ]);

        if ($oldStock != $this->quantity_stock) {
            InventoryLogs::create(attributes: [
                'product_id' => $this->price->product_id,
                'user_id' => auth()->id(),
                'type' => $this->quantity_stock > $oldStock ? 'in' : 'out',
                'quantity' => abs($this->quantity_stock - $oldStock),
                'note' => "Manual stock adjustment for {$this->price->size}: {$oldStock} -> {$this->quantity_stock}"
            ]);
        }

        if ($oldReorder != $this->reorder_level) {
            InventoryLogs::create([
                'product_id' => $this->price->product_id,
                'user_id' => auth()->id(),
                'type' => 'adjustment',
                'quantity' => 0,
                'note' => "Reorder level for {$this->price->size} changed: {$oldReorder} -> {$this->reorder_level}"
            ]);
        }

        $this->dispatch('editInventory'); 
        $this->dispatch('close-edit-modal');
        $this->dispatch('toast.success', 'Inventory updated successfully!');
    }

    public function render()
    {
        return view('livewire.inventory.edit-inventory-form');
    }
}