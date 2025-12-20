<div 
    x-data="{ open: false }"
    x-show="open"
    x-on:open-create-modal.window="open = true"
    x-on:close-create-modal.window="open = false"
    x-transition.opacity
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
>
    <div 
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black/70 backdrop-blur-sm"
        @click="$dispatch('close-create-modal')"
    ></div>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-2xl z-50 overflow-hidden flex flex-col max-h-[90vh]"
    >
        <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 shrink-0">
            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                🛒 Customer Cart
            </h3>
            <button 
                x-on:click="$dispatch('close-create-modal')"
                type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition"
            >
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        
        <div class="flex-grow overflow-hidden flex flex-col md:flex-row">
            
            <section class="p-6 md:w-3/5 overflow-y-auto space-y-4 bg-white dark:bg-gray-800">
                @if ($cart && $cart->items->count() > 0)
                    @foreach ($cart->items as $item)
                        <div class="flex items-center gap-4 rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 p-4 shadow-sm hover:shadow-md transition">
                            
                            <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700/50">
                                <img 
                                    class="w-full h-full object-contain p-1" 
                                    src="{{ isset($item->product->product_image) ? 
                                    asset('storage/' . $item->product->product_image) : asset('storage/images/profiles/default.jpg')}}" 
                                    alt="{{ $item->product?->name }}" 
                                />
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-base font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $item->product?->name }}
                                </p>
                                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                    {{ ucfirst($item->price->size) }}
                                </p>
                            </div>

                            <div class="flex items-center justify-end md:gap-4">
                                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                    <button 
                                        type="button" 
                                        wire:click="decrement({{ $item->price_id }})"
                                        class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-l-lg transition">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 18 2"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" /></svg>
                                    </button>
                                    
                                    <input 
                                        type="text" 
                                        min="1"
                                        wire:model.lazy="items.{{ $loop->index }}.quantity"
                                        wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                        class="w-10 shrink-0 border-y-0 border-x border-gray-300 dark:border-gray-600 bg-transparent text-center text-sm font-semibold text-gray-900 dark:text-white p-0 focus:outline-none focus:ring-0" 
                                        required 
                                    />
                                    
                                    <button 
                                        type="button" 
                                        wire:click="increment({{ $item->price_id }})"
                                        class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-r-lg transition">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 18 18"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" /></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end w-20 shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($item->price?->price * $item->quantity, 2) }}</p>
                                <button 
                                    wire:click="delete('{{ $item->price_id }}')"
                                    type="button" 
                                    class="inline-flex items-center text-xs font-medium text-red-600 hover:text-red-700 dark:text-red-500 hover:underline mt-1 transition">
                                    <i class="ph ph-trash-simple me-1"></i> Remove
                                </button>
                            </div>
                        </div>
                        @endforeach
                @else
                    <div class="rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-10 mt-6 shadow-inner">
                        <p class="text-center text-xl font-medium text-gray-500 dark:text-gray-400">
                            Your cart is empty. Add some products!
                        </p>
                    </div>
                @endif
            </section>
            
            <section class="md:w-2/5 shrink-0 p-6 bg-indigo-50/50 dark:bg-indigo-900/20 border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-700 space-y-6">
                
                <p class="text-xl font-bold text-gray-900 dark:text-white border-b pb-3 border-gray-200 dark:border-gray-700">Order Information</p>

                <div class="space-y-4">
                    
                    <div class="flex items-center justify-between gap-4">
                        <label for="customer_name" class="text-base font-medium text-gray-700 dark:text-gray-300 shrink-0">Customer Name:</label>
                        <input 
                            id="customer_name"
                            wire:model="customer_name"
                            class="w-full text-right text-base py-2 px-3 rounded-lg font-medium text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Guest (Optional)"
                        />
                    </div>
                    
                    <div class="flex items-center justify-between gap-4">
                        <label for="amount_paid" class="text-base font-medium text-gray-700 dark:text-gray-300 shrink-0">Amount Paid:</label>
                        <input 
                            id="amount_paid"
                            wire:model.blur="amount_paid"
                            type="number" 
                            class="w-full text-right text-base py-2 px-3 rounded-lg font-medium text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="₱0.00"
                        />
                    </div>
                    
                    <div class="flex items-center justify-between gap-4">
                        <label for="payment_method" class="text-base font-medium text-gray-700 dark:text-gray-300 shrink-0">Payment Method:</label>
                        <select 
                            id="payment_method"
                            wire:model="paymentMethod" 
                            class="w-full text-center text-sm py-2 px-3 rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                        >
                            <option value="" disabled>Select Method</option>
                            @foreach (App\Models\Order::PAYMENT_METHOD as $payment)
                                <option value="{{ strtoupper($payment) }}">
                                    {{ strtoupper($payment) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="border-t border-gray-300 dark:border-gray-600 pt-6 space-y-3">
                    <p class="text-xl font-bold text-gray-900 dark:text-white">Summary</p>

                    <dl class="flex items-center justify-between gap-4">
                        <dt class="text-base font-normal text-gray-600 dark:text-gray-400">Total Price:</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                            ₱{{ number_format($this->getTotalProperty(), 2) }}
                        </dd>
                    </dl>
                    <dl class="flex items-center justify-between gap-4">
                        <dt class="text-base font-normal text-gray-600 dark:text-gray-400">Amount Given:</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                            ₱{{ number_format($amount_paid == '' ? 0 : $amount_paid, 2) }}
                        </dd>
                    </dl>

                    <dl class="flex items-center justify-between gap-4 border-t border-gray-400 pt-4 dark:border-gray-500">
                        <dt class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            Change
                        </dt>
                        <dd class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            ₱{{ $change }}
                        </dd>
                    </dl>
                </div>

                <div class="mt-8 w-full">
                    <button
                        wire:click="checkout"
                        type="button"
                        {{ $amount_paid == '' || $paymentMethod == '' ? 'disabled' : '' }}
                        class="w-full inline-flex items-center justify-center px-5 py-3 text-base font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition duration-150 shadow-lg shadow-indigo-500/50 
                            disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:cursor-not-allowed"
                    >
                        <i class="ph ph-shopping-cart-simple text-xl me-2"></i>
                        Complete Transaction
                    </button>
                </div>
            </section>
        </div>
    </div>
</div>