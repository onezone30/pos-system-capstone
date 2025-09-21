<div>

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

            <!-- Create Button -->
            <div class="flex justify-end mt-4">
                <x-forms.button>
                    Create Product
                </x-forms.button>
            </div>
        </div>
    </form>

</div>

