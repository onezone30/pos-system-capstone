<div
    id="product-list" 
    class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

    @foreach ($products as $product)
        <x-product-card 
            :product="$product" 
            :categories="$categories" />
    @endforeach


    <!-- rendering modals -->
    <!-- Edit Modal -->
    <x-modals.edit>
        <livewire:product.edit-product-form />
    </x-modals.edit>
    <!-- Delete Modal -->
    <x-modals.delete />


</div>

