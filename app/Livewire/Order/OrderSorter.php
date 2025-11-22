<?php

namespace App\Livewire\Order;

use Livewire\Component;
use App\Models\User;

class OrderSorter extends Component
{
    public $selectedRange = 'today';
    public $order = 'asc';
    public $user;
    public $users;

    public function mount()
    {
        $this->users = User::all();
    }

    public function updatedSelectedRange($range)
    {
        $this->dispatch('rangeChanged', $range);
    }

    public function updatedOrder($order)
    {
        $this->dispatch('orderChanged', $order);
    }

    public function updatedUser($userId)
    {
        $this->dispatch('userChanged', $userId);
    }

    public function render()
    {
        return view('livewire.order.order-sorter');
    }
}
