<?php

namespace App\Livewire;

use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartComponent extends Component
{
    public $items = [];
    public $cart;
    public int $itemCount = 0;
    public ?int $amount_paid = 0;
    public string $paymentMethod = '';
    public int $quantity = 0;

    protected $listeners = [
        'updateCart' => 'loadCart',
        'updateItem' => 'loadCart',
    ];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = Cart::with('items.product', 'items.price')
            ->where('user_id', Auth::id())
            ->first();

        if($this->cart) {
            foreach($this->cart->items as $item) {
                if(! $item->price || $item->price->price <= 0 || !$item->quantity || $item->price->quantity_stock <= 0  || $item->price->price == null) {
                    $item->delete();
                    $this->loadCart();
                }
            }
        }
        
        $this->items = $this->cart?->items->toArray() ?? [];
        $this->itemCount = $this->cart?->items->sum('quantity') ?? 0;

        $this->dispatch('cart-count-update', count: $this->itemCount);
    }

    public function updateQuantity($itemId, $quantity)
    {
        $item = CartItem::where('id', $itemId)
            ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->first();

        if (! $item) {
            $this->dispatch('toast.error', message: 'Item not found');
            return;
        }

        $quantity = (int) $quantity;

        if ($quantity < 1) {
            $this->dispatch('toast.error', message: 'Quantity must be at least 1');
            return;
        }

        if ($item->price && $quantity > $item->price->quantity_stock) {
            $item->update(['quantity' => 1]);

            $this->loadCart();
            
            $this->dispatch('toast.error', message: 'Not enough stock available');
            return;
        }

        $item->update(['quantity' => $quantity]);
        $this->loadCart();
    }

    public function checkout()
    {
        if($this->change < 0) {
            $this->dispatch('toast.error', message: 'Insufficient payment amount');
            $this->reset('amount_paid');
            return;
        }

        if($this->items === []) {
            $this->dispatch('toast.error', message: 'No items in the cart');
            return;
        }

        if($this->cart) {
            $invalidItem = $this->cart->items->filter(fn($item) => $item->price === null || $item->price->price === null);

            if($invalidItem->isNotEmpty()) {
                $this->dispatch('toast.error', message: 'Product has null value. Product were removed from your cart');
                $this->loadCart();
                return;
            }
        }

        if($this->paymentMethod == '') {
            $this->dispatch('toast.error', message: 'Choose payment method');
            return;
        }

        $invalidItem = $this->cart->items->filter(fn($item) =>
            $item->price === null || $item->price->price === null
        );

        if ($invalidItem->isNotEmpty()) {
            $this->dispatch('toast.error', message: 'Product has null value. They were removed from your cart.');
            $this->loadCart();
            return;
        }

        foreach ($this->cart->items as $item) {
            if ($item->quantity > $item->price->quantity_stock) {
                $this->dispatch('toast.error', message: 'Order quantity exceeds available stock for ' . $item->product->name);
                return;
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $this->total,
            'amount_paid' => $this->amount_paid,
            'change' => $this->change,
            'payment_method' => $this->paymentMethod
        ]);

        foreach($this->cart->items as $item) {
            $subtotal = $item->quantity * $item->price?->price;

            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price?->price ?? 0,
                'subtotal' => $subtotal
            ]);

            $item->price->decrement('quantity_stock', $item->quantity);
        }

        $this->dispatch('close-create-modal');
        $this->resetCart();
        $this->dispatch('orderUpdate');
        $this->dispatch('toast.success', message: 'Order successful');
    }

    public function resetCart()
    {
        CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->delete();

        $this->loadCart();

        $this->dispatch('updateCart');
    }

    public function delete($priceId)
    {
        $item = CartItem::where('price_id', $priceId)
            ->whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if($item) {
            $item->delete();
        } else {
            $this->dispatch('toast.error', message: 'Item not found');
        }

        $this->dispatch('updateCart');
    }

    public function decrement($priceId)
    {
        $item = CartItem::where('price_id', $priceId)
            ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->first();

        if(!$item) {
            return;
        }

        if($item && $item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }

        $this->loadCart();
    }

    public function increment($priceId)
    {
        $item = CartItem::where('price_id', $priceId)
            ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->first();

        if($item) {
            $item->increment('quantity');
        } else {
            dd('no item to decrement');
        }

        $this->loadCart();
    }

    public function getTotalProperty()
    {
        return $this->cart?->items->sum(function($item) {
            return $item->price?->price * $item->quantity ?? 0;
        });
    }

    public function getChangeProperty()
    {
        return $this->amount_paid - $this->total;
    }

    public function render()
    {
        return view('livewire.cart-component', [
            'cart' => $this->cart,
            'total' => $this->total,
            'change' => $this->change,
        ]);
    }
}
