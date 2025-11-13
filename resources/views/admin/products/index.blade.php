<x-main>

        <x-page-title text="Products" />

        <div class="flex justify-between items-center">

            <livewire:search placeholder="name, category"/>
            
            <x-button x-data x-on:click="$dispatch('open-create-modal')">
                Add Product
            </x-button>
            
            <!-- add modal -->
            <x-modals.create header="Create Product">
                <livewire:product.create-product-form />  
            </x-modals.create>

        </div>
        
        <x-section>

            <livewire:product.product-list/>

        </x-section>
</x-main>


