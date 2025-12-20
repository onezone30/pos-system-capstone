<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:open-edit-modal.window="
        open = true;
    "
    x-on:close-edit-modal.window="open = false"
    class="p-4 sm:p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl"
>
    <form wire:submit.prevent="update">

        <div class="space-y-6">

            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 border-b pb-3 mb-4 border-gray-200 dark:border-gray-700">
                <span class="text-indigo-600 dark:text-indigo-400">Inventory Details</span>
            </h2>

            <div class="p-5 sm:p-6 bg-gray-50 dark:bg-gray-700/30 rounded-xl shadow-inner border border-gray-200 dark:border-gray-700 space-y-4">
                <div class="flex items-center gap-3 border-b pb-3 mb-2 border-gray-200 dark:border-gray-600">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Selected Product Variant</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <span class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wider">Product Name</span>
                        <span class="text-base font-medium text-gray-800 dark:text-gray-200 truncate">
                            {{ $price?->product->name }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wider">Variant / Size</span>
                        <span class="text-base font-medium text-indigo-600 dark:text-indigo-400 truncate">
                            {{ $price?->size ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4 pt-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Update Stock & Reorder Point</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-forms.input 
                        type="number"
                        wire:model="quantity_stock"
                        label="Quantity in Stock"
                        placeholder="Enter stock quantity"
                    />

                    <x-forms.input 
                        type="number"
                        wire:model="reorder_level"
                        label="Reorder Level (Minimum Stock)"
                        placeholder="Enter reorder level"
                    />
                </div>

                <div class="space-y-1">
                    @error('quantity_stock')
                        <p class="text-red-500 dark:text-red-400 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                    @error('reorder_level')
                        <p class="text-red-500 dark:text-red-400 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button 
                    size="lg" 
                    color="indigo" 
                    type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="update"
                >
                    <span wire:loading.remove wire:target="update">Save Changes</span>
                    <span wire:loading wire:target="update">Saving...</span>
                </x-button>
            </div>

        </div>
    </form>
</div>