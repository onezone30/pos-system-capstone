<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:open-edit-modal.window="open = true"
    x-on:close-edit-modal.window="open = false"
>
    <form wire:submit.prevent="update">

        <div class="space-y-6">

            <!-- Order Items -->
            <div class="space-y-4 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Items</h3>

                @forelse ($items as $index => $item)
                    <div
                        wire:key="item-{{ $item['id'] ?? $index }}" 
                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ $item['name'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                ₱{{ number_format($item['subtotal'], 2) }} (₱{{ number_format($item['price'], 2)}} each)
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Decrement -->
                            <button 
                                type="button"
                                wire:click="decrement({{ $index }})"
                                class="px-3 py-1 rounded-full bg-red-500 hover:bg-red-600 text-white font-bold"
                            >-</button>

                            <!-- Quantity -->
                            <span class="font-semibold text-gray-800 dark:text-white">
                                {{ $item['quantity'] }}
                            </span>

                            <!-- Increment -->
                            <button 
                                type="button"
                                wire:click="increment({{ $index }})"
                                class="px-3 py-1 rounded-full bg-green-500 hover:bg-green-600 text-white font-bold"
                            >+</button>

                            <!-- Remove -->
                            <x-button 
                                wire:click="delete({{ $index }})"
                                color="red"
                            >
                                Remove
                            </x-button>
                        </div>
                    </div>
                @empty
                    <p class="text-lg text-center font-bold text-gray-500 dark:text-gray-400">No items in this order.</p>
                @endforelse
            </div>

            <x-forms.input 
                wire:model.live="customer_name"
                label="Customer Name"
                type="text"
                placeholder="Enter customer name"
            />

            <!-- Amount Paid -->
            <x-forms.input 
                wire:model.live="amount_paid"
                label="Amount Paid"
                type="number"
                placeholder="Enter amount paid"
            />

            <div class="flex justify-between items-center text-gray-800 dark:text-gray-200">
                <span class="font-medium">Total:</span>
                <span class="font-semibold text-lg">₱{{ number_format($total, 2) }}</span>
            </div>

            <div class="flex justify-between items-center text-gray-800 dark:text-gray-200">
                <span class="font-medium">Change:</span>
                @if ($amount_paid - $total > 0)
                <span class="font-semibold text-lg text-green-600 dark:text-green-400">
                    ₱{{ number_format($amount_paid - $total, 2) }}
                </span>
                @else
                    <span class="font-semibold text-lg text-red-600 dark:text-red-400">
                        ₱{{ number_format($amount_paid - $total, 2) }}
                    </span>
                @endif
            </div>

            <!-- Payment Method -->
            <x-forms.select label="Payment Method" wire:model="payment_method">
                <option value="cash">Cash</option>
                <option value="gcash">GCash</option>
                <option value="maya">Maya</option>
            </x-forms.select>

            <!-- Update Button -->
            <div class="flex justify-end mt-6">
                <x-button size="2xl" color="blue" wire:click="update" wire:target="update">
                    Update Order
                </x-button>
            </div>

        </div>
    </form>
</div>
