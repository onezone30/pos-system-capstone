<!-- Main -->
<div 
    x-data="{ open: false }"
    x-show="open"
    x-on:open-create-modal.window="open = true"
    x-on:close-create-modal.window="open = false"
    x-transition.opacity
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <!-- overlay -->
    <div 
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black/50"
        @click="$dispatch('close-create-modal')"
    ></div>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 -translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-10 scale-95"
        class="relative w-full max-w-2xl mx-auto bg-white dark:bg-gray-700 rounded-lg shadow-lg z-50"
    >
        <!-- header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 dark:border-gray-600">
            <h3 class="text-3xl font-semibold text-gray-900 dark:text-white">
                Cart
            </h3>
            <button 
                x-on:click="$dispatch('close-create-modal')"
                type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            >
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        

        <!-- Modal body -->
        <section class="antialiased p-4 overflow-y-auto max-h-[80vh]">
            <div class="space-y-6">
                @if ($cart && $cart->items->count() > 0)
                    @foreach ($cart->items as $item)
                        <!-- Item -->
                        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                <img 
                                    class="h-20 w-20" 
                                    src="{{ isset($item->product->product_image) ?  
                                    asset('storage/' . $item->product->product_image) : asset('storage/images/profiles/default.jpg')}}" 
                                    alt="{{ $item->product?->name }}" />

                            <label for="counter-input" class="sr-only">Choose quantity:</label>
                            <div class="flex items-center justify-between md:order-3 md:justify-end">
                                <div class="flex items-center">
                                <button 
                                    type="button" 
                                    wire:click="decrement({{ $item->price_id }})"
                                    class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                    <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                    </svg>
                                </button>
                                <input 
                                    type="text" 
                                    min="1"
                                    wire:model.lazy="items.{{ $loop->index }}.quantity"
                                    wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                    class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white" 
                                    required />
                                <button 
                                    type="button" 
                                    wire:click="increment({{ $item->price_id }})"
                                    class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                    <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                    </svg>
                                </button>
                                </div>
                                <div class="text-end md:order-4 md:w-32">
                                    <p class="text-base font-bold text-gray-900 dark:text-white">{{ $item->price?->price }}</p>
                                </div>
                            </div>

                            <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                <a href="#" class="text-base font-medium text-gray-900 hover:underline dark:text-white">
                                    <span class="font-bold">{{ $item->product?->name }}</span> 
                                    <span class="text-gray-400">({{ ucfirst($item->price->size) }})</span>
                                </a>

                                <div class="flex items-center gap-4">
                                    <button 
                                        wire:click="delete('{{ $item->price_id }}')"
                                        type="button" 
                                        class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                        <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="text-center text-xl text-red-500">
                            No items
                        </p>
                    </div>
                @endif
            </div>

            <!-- Summary Part -->
            <div>
                <div class="mt-6 space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                    <div class="space-y-4">
                        <dl class="w-full flex items-center justify-between gap-4">
                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">
                                Amount Paid
                            </dt>
                            <dd class="flex-1 max-w-[100px]">
                                <input 
                                    wire:model.blur="amount_paid"
                                    type="number" 
                                    class="w-full text-right text-base bg-black/50 py-2 px-4 rounded-lg font-medium text-gray-900 dark:text-white border-none focus:ring-0 focus:outline-none"
                                    placeholder="₱0.00"
                                />
                            </dd>
                        </dl>
                        <dl class="w-full flex items-center justify-between gap-4">
                            <dt class="w-3/5 text-base font-normal text-gray-500 dark:text-gray-400">
                                Payment Method
                            </dt>
                            <dd class="flex-1">
                                <select wire:model="paymentMethod" class="w-full text-center bg-black/50 py-2 px-4 rounded-lg">
                                    <option value="" disabled selected>Payment method</option>
                                    @foreach (App\Models\Order::PAYMENT_METHOD as $payment)
                                        <option value="{{ strtoupper($payment) }}" class="uppercase">
                                            {{ strtoupper($payment) }}
                                        </option>
                                    @endforeach
                                </select>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-6 space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">Order summary</p>

                    <div class="space-y-4">
                        <div class="space-y-2">                            
                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">
                                    Amount Paid
                                </dt>
                                <dd class="flex-1">
                                    <p class="w-full text-right text-base font-medium text-gray-900 dark:text-white border-none focus:ring-0 focus:outline-none">
                                        {{ $amount_paid == '' ? 0 : $amount_paid }}
                                    </p>
                                </dd>
                            </dl>
                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">
                                    Total Price
                                </dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">
                                    ₱{{ number_format($this->getTotalProperty(), 2) }}
                                </dd>
                            </dl>
                        </div>

                        <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                            <dt class="text-base font-bold text-gray-900 dark:text-white">
                                Change
                            </dt>
                            <dd class="text-base font-bold text-gray-900 dark:text-white">
                                ₱{{ $change }}
                            </dd>
                        </dl>
                    </div>

                    <div class="mt-8 w-full flex justify-end">
                        <x-button
                            wire:click="checkout">
                            Proceed to Checkout
                        </x-button>
                    </div>
                </div> 
            </div>
        </section>
    </div>
</div>

