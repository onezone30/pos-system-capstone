<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:open-create-modal.window="open = true"
    x-on:close-create-modal.window="open = false"
>

    <form wire:submit.prevent="create">

        <div class="space-y-6">

            <!-- Names -->
            <x-forms.input 
                wire:model="name"
                label="Name"
                placeholder="Enter name of product"/>

            <!-- Categories -->
            <x-forms.select label="Select category" wire:model="category_id">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-forms.select>

            <!-- Dynamic Sizes -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Sizes</h2>
                    <x-button color="green" size="sm" wire:click.prevent="addSize">+ Add Size</x-button>
                </div>

                @foreach ($sizes as $index => $size)
                    <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 space-y-3 relative">
                        <div class="absolute top-2 right-2">
                            <x-button 
                                color="red" 
                                size="xs"
                                wire:click.prevent="removeSize({{ $index }})"
                            >
                                Remove
                            </x-button>
                        </div>

                        <x-forms.input 
                            wire:model="sizes.{{ $index }}.name"
                            label="Size Name"
                            placeholder="e.g. Small"
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <x-forms.input 
                                wire:model="sizes.{{ $index }}.price"
                                label="Price"
                                placeholder="Enter price"
                            />
                            <x-forms.input 
                                wire:model="sizes.{{ $index }}.quantity"
                                label="Quantity"
                                placeholder="Enter quantity stock"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
            
            <x-forms.file 
                accept="image/*"
                label="Profile Picture"
                wire:model="product_image"/>

            @if ($product_image && !$product_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <div class="flex items-center justify-center gap-4">
                    <img 
                        src="{{ asset('storage/' . $product_image) }}" 
                        alt="Product Image"
                        class="max-h-48 rounded-lg object-cover border"
                    >

                    <x-button 
                        size="sm" 
                        color="red" 
                        wire:click="removeProductImage"
                        wire:loading.attr="disabled"
                    >
                        Remove
                    </x-button>
                </div>
            @endif

            @if ($product_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <div class="flex items-center justify-center gap-4">
                    <img 
                        src="{{ $product_image->temporaryUrl() }}" 
                        alt="Preview" 
                        class="max-h-48 rounded-lg object-cover border"
                    >
                    <x-button 
                        size="sm" 
                        color="red" 
                        wire:click="$set('product_image', null)"
                    >
                        Cancel Upload
                    </x-button>
                </div>
            @endif

            <!-- Edit Button -->
            <div class="flex justify-end mt-4">
                 <x-button size="2xl" color="blue" wire:click="create" wire:target="create">
                    Create Product
                </x-button>
            </div>
        </div>
    </form>
</div>