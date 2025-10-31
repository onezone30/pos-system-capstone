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

            @foreach ($sizes as $index => $size)
                <h1 class="text-xl">
                    {{ ucfirst($size) }} Size
                </h1>
                <div class="grid grid-cols-2 gap-4">
                    <x-forms.input 
                        wire:model="prices.{{ $index }}"
                        placeholder="Enter price"/>
                    <x-forms.input 
                        wire:model="quantities.{{ $index }}"
                        placeholder="Enter quantity stock"/>
                </div>
            @endforeach
            
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
                <div class="flex items-center gap-4">
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