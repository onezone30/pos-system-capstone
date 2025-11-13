<?php

namespace App\Livewire\Sales;

use Livewire\Component;

class SaleSorter extends Component
{
    public $selectedRange = 'today';
    public $order = 'asc';

    public function updatedSelectedRange($range)
    {
        $this->dispatch('rangeChanged', range: $range);
    }

    public function updatedOrder($order)
    {
        $this->dispatch('orderChanged', order: $order);
    }

    public function render()
    {
        return view('livewire.sales.sale-sorter');
    }
}
