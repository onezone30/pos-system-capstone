<x-main>

        <h1 class="text-2xl font-bold">
            Create Order
        </h1>

        <div class="flex justify-between items-center">

            <livewire:search placeholder="name, category"/>
            

            <x-button
                x-on:cart-count-update.window="count = $event.detail.count" 
                x-data="{ count: '0' }"
                x-on:click="$dispatch('open-create-modal');"
            >
                Check Cart
                <span
                    x-text="count" 
                    class="inline-flex items-center justify-center w-4 h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
                </span>
            </x-button>
            
            <livewire:cart-component />
            
        </div>
        
        <x-section>
            <livewire:product.product-sorter />
            <livewire:product-order-list :products="$products"/>
        </x-section>
        


</x-main>


