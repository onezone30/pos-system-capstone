<?php

namespace App\Livewire\Order;

use App\Models\Order;
use App\Services\OrderServices;
use Livewire\Attributes\On;
use Livewire\Component;

class EditOrderForm extends Component
{
    public Order $order;
    public float $total = 0;
    public int $amount_paid = 0;
    public string $customer_name = 'Guest';
    public $items = [];
    public string $payment_method = '';
    public float $subtotal = 0;

    public function rules()
    {
        return [
            'amount_paid' => ['required', 'numeric', 'gt:total'],
            'total' => ['required', 'numeric',],
            'payment_method' => ['string', 'required'],
            'customer_name' => ['string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.subtotal' => ['required', 'numeric', 'min:0'],
        ];
    }

    #[On('open-edit-modal')]
    public function load($id)
    {
        $this->order = Order::with('items.product')->findOrFail($id);

        $this->payment_method = $this->order->payment_method;
        $this->customer_name = $this->order->customer_name;
        $this->total = $this->order->total ?? 0;
        $this->amount_paid = $this->order->amount_paid ?? 0;

        $this->items = $this->order->items
            ->map(function ($item) {
                $product = $item->product;

                return [
                    'id' => $item->id,
                    'name' => $product?->name ?? 'Unknown Product',
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                    'quantity' => $item->quantity,
                ];
            })
            ->toArray();


        $this->calculateTotal();
    }

    protected function calculateTotal()
    {
        $this->total = collect($this->items)
            ->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    protected function updateItemSubtotal($index)
    {
        $item = &$this->items[$index];
        $item['subtotal'] = $item['price'] * $item['quantity'];
    }

    public function increment($id)
    {
        $this->items[$id]['quantity']++;
        $this->updateItemSubtotal($id);
        $this->calculateTotal();
    }

    public function decrement($id)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        if ($this->items[$id]['quantity'] <= 1) {
            $this->dispatch('toast.error', message: "Quantity cannot be less than 1");
            return;
        }

        $this->items[$id]['quantity']--;

        $this->updateItemSubtotal($id);
        $this->calculateTotal();
    }

    public function delete($id)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        unset($this->items[$id]);
        $this->items = array_values($this->items); // Reindex the array

        $this->calculateTotal();
    }

    public function update(OrderServices $service)
    {
        $this->validate();

        $data = [
            'payment_method' => $this->payment_method,
            'total' => $this->total,
            'amount_paid' => $this->amount_paid,
            'items' => $this->items,
        ];

        $service->update($this->order, $data);

        $this->dispatch('toast.success', message: "Order No.{$this->order->id} has been updated");
        $this->dispatch('editOrder');
        $this->dispatch('close-edit-modal');
    }

    public function render()
    {
        return view('livewire.order.edit-order-form');
    }
}
