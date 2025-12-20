<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:open-edit-modal.window="open = true"
    x-on:close-edit-modal.window="open = false"
    class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 dark:bg-gray-900/70 backdrop-blur-sm"
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-xl p-6 md:p-8"
            @click.away="open = false"
        >
            <div class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Order</h2>
                <button 
                    @click="open = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                >
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="update" class="mt-6 space-y-6">

                <section class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-2">
                        Order Items
                    </h3>

                    <div class="max-h-80 overflow-y-auto pr-2 space-y-3">
                        @forelse ($items as $index => $item)
                            <div
                                wire:key="item-{{ $item['id'] ?? $index }}" 
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm transition-shadow hover:shadow-md"
                            >
                                <div class="mb-2 sm:mb-0 sm:w-1/2">
                                    <p class="font-bold text-gray-800 dark:text-white truncate">
                                        {{ $item['name'] }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Unit Price: ₱{{ number_format($item['price'], 2)}}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between w-full sm:w-auto sm:gap-4">
                                    <div class="flex items-center gap-1 bg-white dark:bg-gray-800 rounded-full p-0.5 shadow-inner">
                                        <button 
                                            type="button"
                                            wire:click="decrement({{ $index }})"
                                            class="p-2 rounded-full bg-red-100 dark:bg-red-900/30 hover:bg-red-500 dark:hover:bg-red-600 text-red-600 dark:text-red-400 hover:text-white transition"
                                        >
                                            <i class="ph ph-minus w-4 h-4"></i>
                                        </button>

                                        <span class="font-bold text-gray-900 dark:text-white px-2 min-w-[30px] text-center">
                                            {{ $item['quantity'] }}
                                        </span>

                                        <button 
                                            type="button"
                                            wire:click="increment({{ $index }})"
                                            class="p-2 rounded-full bg-green-100 dark:bg-green-900/30 hover:bg-green-500 dark:hover:bg-green-600 text-green-600 dark:text-green-400 hover:text-white transition"
                                        >
                                            <i class="ph ph-plus w-4 h-4"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <p class="font-extrabold text-lg text-indigo-600 dark:text-indigo-400 min-w-[100px] text-right">
                                            ₱{{ number_format($item['subtotal'], 2) }}
                                        </p>
                                        
                                        <button
                                            type="button"
                                            wire:click="delete({{ $index }})"
                                            class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition ml-2 p-1"
                                            title="Remove Item"
                                        >
                                            <i class="ph ph-trash w-6 h-6"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                                <i class="ph ph-package w-10 h-10 mx-auto text-gray-400 mb-2"></i>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No items in this order.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
                
                <section class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer & Payment</h3>

                    <x-forms.input 
                        wire:model.live="customer_name"
                        label="Customer Name"
                        type="text"
                        placeholder="Enter customer name"
                    />

                    <x-forms.input 
                        wire:model.live="amount_paid"
                        label="Amount Paid (₱)"
                        type="number"
                        placeholder="Enter amount paid"
                    />

                    <x-forms.select label="Payment Method" wire:model="payment_method">
                        <option value="cash">Cash</option>
                        <option value="gcash">GCash</option>
                        <option value="maya">Maya</option>
                    </x-forms.select>
                </section>
                
                <section class="space-y-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center text-gray-800 dark:text-gray-200">
                        <span class="font-medium text-lg">Total:</span>
                        <span class="font-extrabold text-2xl text-indigo-600 dark:text-indigo-400">₱{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg text-gray-800 dark:text-gray-200">Change:</span>
                        @if ($amount_paid - $total >= 0)
                            <span class="font-extrabold text-2xl text-green-600 dark:text-green-400">
                                ₱{{ number_format($amount_paid - $total, 2) }}
                            </span>
                        @else
                            <span class="font-extrabold text-2xl text-red-600 dark:text-red-400">
                                ₱{{ number_format($amount_paid - $total, 2) }}
                            </span>
                        @endif
                    </div>
                </section>

                <div class="flex justify-end pt-4">
                    <x-button size="2xl" color="indigo" wire:click="update" wire:target="update" class="w-full">
                        <i class="ph ph-pencil-simple text-xl mr-2"></i>
                        Update Order
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>