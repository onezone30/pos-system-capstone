<x-main :user="$user">

        <div class="flex justify-between items-center">

            <h1 class="text-2xl font-bold">
                Products
            </h1>

            <x-button data-modal-target="crud-modal" data-modal-toggle="crud-modal">
                Add Product
            </x-button>
            
            <!-- add modal -->
            <x-modals.create header="Create Product">

                @livewire('product.create-product-form')
                
            </x-modals.create>

        </div>
        
        <livewire:product.product-list />


</x-main>


