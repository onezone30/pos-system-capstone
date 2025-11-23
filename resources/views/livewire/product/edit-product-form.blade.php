<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:open-edit-modal.window="open = true"
    x-on:close-edit-modal.window="open = false"
>

    <form wire:submit.prevent="update">

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

            <div class="space-y-4">
                @foreach ($sizes as $index => $size)
                    <div class="border p-4 rounded-lg space-y-3">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold">Size {{ $index + 1 }}</h2>
                            <x-button size="sm" color="red" wire:click="removeSize({{ $index }})">
                                Remove
                            </x-button>
                        </div>

                        <x-forms.input 
                            wire:model="sizes.{{ $index }}"
                            label="Size Name"
                            placeholder="Enter size name"/>

                        <div class="grid grid-cols-3 gap-4">
                            <x-forms.input 
                                wire:model="prices.{{ $index }}"
                                placeholder="Enter price"/>

                            <x-forms.input 
                                wire:model="quantities.{{ $index }}"
                                placeholder="Enter quantity"/>

                            <x-forms.input 
                                wire:model="reorder_levels.{{ $index }}"
                                placeholder="Reorder level"/>
                        </div>
                    </div>
                @endforeach

                <x-button color="green" wire:click="addSize">
                    + Add Size
                </x-button>
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
            @elseif ($product_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <div class="flex items-center justify-center gap-4">
                    <img 
                        src="{{ $product_image->temporaryUrl() }}" 
                        alt="Preview" 
                        class="max-h-48 rounded-lg object-cover border"
                    >
                    <x-button 
                        size="sm" 
                        color="red" 
                        wire:click="removeProductImage"
                    >
                        Remove
                    </x-button>
                </div>
            @endif

            <!-- Edit Button -->
            <div class="flex justify-end mt-4">
                 <x-button size="2xl" color="blue" wire:click="update" wire:target="update">
                    Update Product
                </x-button>
            </div>
        </div>
    </form>
</div>