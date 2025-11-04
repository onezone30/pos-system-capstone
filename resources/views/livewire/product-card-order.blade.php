<div class="space-y-6">
    
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
        <div class="group relative flex flex-col h-[400px] bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 overflow-hidden">

            <!-- Image -->
            <div class="h-1/2 relative overflow-hidden bg-gray-50 dark:bg-gray-700 flex justify-center items-center">
            @if ($product->product_image === null)
                <img 
                    class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-300"
                    src="{{ asset('storage/images/products/default.png') }}"
                    alt="{{ $product->name }}" 
                />
            @else
                <img 
                    class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-300"
                    src="{{ asset('storage/' . $product->product_image) }}"
                    alt="{{ $product->name }}" 
                />
            @endif
            </div>

            <!-- Content -->
            <div class="flex flex-col flex-1 px-5 pb-4">
                <h5 class="my-2 text-2xl font-bold text-center text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200 truncate">
                    {{ $product->name }}
                </h5>

                <!-- Prices -->
                <div class="mt-3 space-y-3">
                    @if($product->prices && $product->prices->count() > 0)
                        @foreach ($product->prices as $price)
                            @if (!empty($price->price) && $price->price != 0)
                                <div
                                    wire:click="addToCart({{ $price->id }})"
                                    class="grid grid-cols-3 items-center bg-gray-100 dark:bg-gray-700 rounded-xl px-4 py-3 text-center shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer">

                                    <!-- Size -->
                                    <div class="flex flex-col">
                                        <span class="text-[11px] uppercase text-gray-500 dark:text-gray-400 tracking-wider">
                                            Size
                                        </span>
                                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 capitalize">
                                            {{ $price->size }}
                                        </span>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="flex flex-col border-x border-gray-200 dark:border-gray-600">
                                        <span class="text-[11px] uppercase text-gray-500 dark:text-gray-400 tracking-wider">
                                            Stock
                                        </span>
                                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            {{ number_format($price->quantity_stock) ?? '—' }}
                                        </span>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex flex-col">
                                        <span class="text-[11px] uppercase text-gray-500 dark:text-gray-400 tracking-wider">
                                            Price
                                        </span>
                                        <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            ₱{{ number_format($price->price, 2) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-center text-sm text-gray-400 italic py-4">Not available</p>
                    @endif
                </div>

            </div>

            <!-- Subtle overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-transparent to-transparent group-hover:from-indigo-50/10 group-hover:to-transparent transition-all duration-300 pointer-events-none rounded-2xl"></div>
        </div>
    </div>
</div>

