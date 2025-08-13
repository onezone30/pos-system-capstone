<x-layout>

    <x-sidebar :role="$user->role"/>

    <main class="p-4 sm:ml-64 mt-14">

        <div class="flex justify-between items-center">

        
            <h1 class="text-2xl font-bold">
                Products
            </h1>
            
            <form action="">

                

                <x-button>
                    Add Product
                </x-button>
            </form>

            

        </div>
        <div class="mt-5 grid grid-cols-4 gap-y-4 gap-x-2">

            @foreach ($products as $product)
                <x-product-card :product="$product"/>
            @endforeach

        </div>

    </main>

</x-layout>