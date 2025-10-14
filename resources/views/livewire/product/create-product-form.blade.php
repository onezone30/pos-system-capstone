<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-10"
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
                <option value="">-- Select category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-forms.select>

            <!-- Sizes -->
            @foreach ($sizes as $index => $size)
                <h1 class="font-bold text-base">
                    {{ ucfirst($size) }} Size
                </h1>
                <div class="grid grid-cols-2 gap-4">
                    {{-- Price --}}
                    <x-forms.input 
                        wire:model="prices.{{ $index }}"
                        placeholder="Enter price"/>
                    {{-- Quantity --}}
                    <x-forms.input 
                        wire:model="quantities.{{ $index }}"
                        placeholder="Enter quantity stock"/>
                </div>
            @endforeach

            <x-forms.file 
                label="Profile Picture"
                wire:model="product_image"/>

            <!-- Create Button -->
            <div class="flex justify-end mt-4">
                <x-button size="2xl" color="blue" wire:click="create" wire:target="create">
                    Create Product
                </x-button>
            </div>
        </div>
    </form>
</div>