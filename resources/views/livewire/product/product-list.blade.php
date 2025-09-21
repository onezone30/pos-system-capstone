<div>
    <div
        id="product-list" 
        class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        @foreach ($products as $product)
            <x-product-card 
                :product="$product" 
                :categories="$categories"
                wire:key="product-card-{{ $product->id }}"/>

            <!-- Edit Modal -->
            <x-modals.edit 
                id="{{ $product->id }}" 
                header="Edit {{ $product->name }}"
                wire:key="delete-modal-{{ $product->id }}"
                >
                @livewire('product.edit-product-form', ['product' => $product])
            </x-modals.edit>
        @endforeach

        <!-- Delete Modal -->
        <x-modals.delete action="delete" />

    </div>

</div>
