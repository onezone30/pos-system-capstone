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
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto"
            @click.away="$dispatch('close-edit-modal')"
        >
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Product</h2>
                <button 
                    @click="$dispatch('close-edit-modal')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                >
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="update">
                <div class="space-y-6">

                    <section class="space-y-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Product Information</h3>
                        
                        <x-forms.input 
                            wire:model="name"
                            label="Product Name"
                            placeholder="Enter name of product"
                        />

                        <x-forms.select label="Select Category" wire:model="category_id">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </section>

                    <section class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white pt-2 border-t border-gray-100 dark:border-gray-700">
                            Sizes & Inventory Details
                        </h3>

                        <div class="space-y-4">
                            @foreach ($sizes as $index => $size)
                                <div wire:key="size-{{ $index }}" class="p-4 rounded-xl space-y-3 shadow-md border border-indigo-200 dark:border-indigo-800 bg-white dark:bg-gray-800 transition-shadow">
                                    <div class="flex justify-between items-center pb-2 border-b dark:border-gray-700">
                                        <h4 class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Size #{{ $index + 1 }}</h4>
                                        <x-button size="sm" color="red" wire:click="removeSize({{ $index }})" title="Remove this size">
                                            <i class="ph ph-trash-simple mr-1"></i> Remove
                                        </x-button>
                                    </div>

                                    <x-forms.input 
                                        wire:model="sizes.{{ $index }}"
                                        label="Size Name (e.g., Small, Medium, 1 Liter)"
                                        placeholder="Enter size name"
                                    />

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <x-forms.input 
                                            wire:model="prices.{{ $index }}"
                                            label="Price (₱)"
                                            type="number"
                                            placeholder="Enter price"
                                        />

                                        <x-forms.input 
                                            wire:model="quantities.{{ $index }}"
                                            label="Stock Quantity"
                                            type="number"
                                            placeholder="Enter quantity"
                                        />

                                        <x-forms.input 
                                            wire:model="reorder_levels.{{ $index }}"
                                            label="Reorder Level"
                                            type="number"
                                            placeholder="Reorder level"
                                        />
                                    </div>
                                </div>
                            @endforeach

                            <x-button color="green" wire:click="addSize" class="w-full justify-center">
                                <i class="ph ph-plus-circle text-xl mr-2"></i> Add New Size Option
                            </x-button>
                        </div>
                    </section>
                    
                    <section class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Product Image</h3>
                        
                        <x-forms.file 
                            accept="image/*"
                            label="Upload Product Image"
                            wire:model="product_image"
                        />

                        <div class="flex items-end gap-6 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border dark:border-gray-700">
                            @if ($product_image && !$product_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <img 
                                    src="{{ asset('storage/' . $product_image) }}" 
                                    alt="Current Product Image"
                                    class="max-h-36 w-auto rounded-lg object-cover shadow-lg border border-gray-300 dark:border-gray-600"
                                >
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Current Image Attached</p>
                                    <x-button 
                                        size="sm" 
                                        color="red" 
                                        wire:click="removeProductImage"
                                        wire:loading.attr="disabled"
                                    >
                                        <i class="ph ph-trash-simple text-lg mr-1"></i> Remove Current
                                    </x-button>
                                </div>
                            
                            @elseif ($product_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <img 
                                    src="{{ $product_image->temporaryUrl() }}" 
                                    alt="New Image Preview" 
                                    class="max-h-36 w-auto rounded-lg object-cover shadow-lg border border-gray-300 dark:border-gray-600"
                                >
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">New Image Ready to Upload</p>
                                    <x-button 
                                        size="sm" 
                                        color="red" 
                                        wire:click="removeProductImage"
                                    >
                                        <i class="ph ph-x text-lg mr-1"></i> Cancel Upload
                                    </x-button>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No image currently set.</p>
                            @endif
                        </div>
                    </section>

                    <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                        <x-button size="2xl" color="blue" wire:click="update" wire:target="update" class="w-full sm:w-auto">
                            <i class="ph ph-floppy-disk text-xl mr-2"></i> Save Changes
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>