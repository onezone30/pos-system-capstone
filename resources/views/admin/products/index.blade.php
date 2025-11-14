<x-main>

        <x-page-title text="Products" />

        <div
            x-data="{ search: '' }" 
            class="mt-4 flex justify-between items-center">

            <input
                x-model="search"
                @input="$dispatch('product-search', search)"
                class="px-4 py-2 border rounded-lg input input-bordered w-full max-w-xs"
                placeholder="Search products..." /> <!-- search bar -->
        
            <x-button x-data x-on:click="$dispatch('open-create-modal')">
                Add Product
            </x-button>
            
            <!-- add modal -->
            <x-modals.create header="Create Product">
                <livewire:product.create-product-form />  
            </x-modals.create>

        </div>
        
        <x-section>

            <livewire:product.product-list />

        </x-section>
</x-main>


