<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:open-edit-modal.window="
        open = true;
    "
    x-on:close-edit-modal.window="open = false"
    class="p-6"
>
    <form wire:submit.prevent="update">

        <div class="space-y-6">

            <!-- Title -->
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                Edit Inventory
            </h2>

            <!-- Product Info (readonly) -->
            <div class="border p-4 rounded-lg space-y-3 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-lg font-semibold">Product Details</h3>

                <p><strong>Name:</strong> {{ $product?->name }}</p>
                <p><strong>Variant:</strong> {{ $price?->size ?? 'N/A' }}</p>
            </div>

            <!-- Reorder Level -->
            <x-forms.input 
                type="number"
                wire:model="reorder_level"
                label="Reorder Level"
                placeholder="Enter reorder level"
            />

            <!-- Quantity Stock -->
            <x-forms.input 
                type="number"
                wire:model="quantity_stock"
                label="Quantity in Stock"
                placeholder="Enter stock quantity"
            />

            @error('reorder_level')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            @error('quantity_stock')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <!-- Update Button -->
            <div class="flex justify-end mt-4">
                <x-button 
                    size="2xl" 
                    color="blue" 
                    wire:click="update" 
                    wire:target="update"
                >
                    Update
                </x-button>
            </div>

        </div>
    </form>
</div>
