<div
    id="product-list" 
    class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

    @foreach ($products as $product)
    <div
        wire:key="product-card-{{ $product->id }}"
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 200)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 -translate-y-10"
        x-transition:leave="transition ease-in duration-300"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-10"                     
        >
        <x-product-card 
            :product="$product" 
            :categories="$categories" />
    </div>

    @endforeach


    <!-- rendering modals -->
    <!-- Delete Modal -->
    <x-modals.delete action="delete" />


</div>

