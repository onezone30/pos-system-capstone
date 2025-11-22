<x-main>
    <x-page-title text="Order" />


        <div class="flex justify-between items-center">

            <livewire:order.order-sorter />    

        </div>
        
        <x-section>

            <livewire:order.order-list :orders="$orders"/>

        </x-section>
        


</x-main>