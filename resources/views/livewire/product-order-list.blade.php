<div>
    <div class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @foreach ($products as $product)
            <livewire:product-card-order 
                :product="$product" 
                :key="$product->id"
            />
        @endforeach


    </div>
    <div class="mt-6 flex justify-center">
        {{ $products->links() }}
    </div>
</div>