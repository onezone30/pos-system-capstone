<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;
use App\Models\ProductPrices;
use Illuminate\Support\Facades\Auth;

class ProductCardOrder extends Component
{
    public $product;

    protected $listeners = [
        'orderUpdate' => '$refresh'
    ];

    public function mount($product)
    {
        $this->product = $product;
    }

    public function addToCart($priceId)
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $item =  $cart->items()->where('price_id', $priceId)->first();

        if($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            $cart->items()->create([
                'user_id' => $user->id,
                'cart_id' => $cart->id,
                'product_id' => ProductPrices::find($priceId)->product_id,
                'price_id' => $priceId,
                'quantity' => 1,
            ]);
        }
        $this->dispatch('toast.success', message: "Product {$this->product->name} has been added to cart");
        $this->dispatch('updateItem')->to(CartComponent::class);
    }

    public function render()
    {
        return view('livewire.product-card-order');
    }
}
