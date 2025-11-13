<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        
        $order->delete();

        $this->dispatch('close-delete-modal');
        $this->dispatch('deleteOrder');
        $this->dispatch('toast.success', message: "Order No.{$order->id} has been deleted");
    }

    #[On(['editOrder', 'deleteOrder'])]
    public function render()
    {
        $orders = Order::with(['items.product', 'user'])->paginate(5);

        return view('livewire.order.order-list', [
            'orders' => $orders,
        ]);
    }
}
