@props(['product', 'categories' => false])
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
    <div class="group relative flex flex-col h-full bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        
        <!-- Image Container with Overlay -->
        <div class="relative overflow-hidden">
            <div class="aspect-square p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
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
            
            <!-- Floating Badge -->
            <div class="absolute top-4 right-4 bg-indigo-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                {{ $product->prices ? $product->prices->count() : 0 }} sizes
            </div>
        </div>

        <!-- Content Section -->
        <div class="flex flex-col h-full p-6">
            
            <!-- Product Name -->
            <h5 class="mb-3 text-xl font-bold text-center text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200 whitespace-nowrap overflow-hidden text-ellipsis">
                {{ $product->name }}
            </h5>

            <h5 class="mb-3 text-base font-bold text-center text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                {{ $product->category?->name ?? 'No Category' }}
            </h5>

            <!-- Prices Section -->
            <div class="min-h-[120px] mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        Available Sizes
                    </h6>
                </div>
                
                @if($product->prices && $product->prices->count() > 0)
                    <div class="space-y-2">
                        @foreach ($product->prices as $price)

                            @if (!empty($price->price) && $price->price != 0)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <span class="font-medium text-gray-700 dark:text-gray-300 capitalize">
                                        {{ $price->size }}
                                    </span>
                                    <span class="font-bold text-lg text-indigo-600 dark:text-indigo-400">
                                        ₱{{ number_format($price->price, 2) }}
                                    </span>
                                </div>
                            @endif

                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="text-gray-400 mb-2">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">No prices available</p>
                    </div>
                @endif
            </div>


            <!-- Quantity Stock Section -->
            <div class="min-h-[120px] mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        Quantity Stock
                    </h6>
                </div>
                
                @if($product->prices && $product->prices->count() > 0)
                    <div class="space-y-2">
                        @foreach ($product->prices as $price)
                            @if ($price->quantity_stock !== 0)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <span class="font-medium text-gray-700 dark:text-gray-300 capitalize">
                                        {{ $price->size }}
                                    </span>
                                    @if($price->quantity_stock < 20)
                                    <span class="font-bold text-lg text-red-600 dark:text-red-400">
                                        {{ number_format($price->quantity_stock) }}
                                    </span>
                                    @elseif ($price->quantity_stock < 40)
                                    <span class="font-bold text-lg text-yellow-600 dark:text-yellow-400">
                                        {{ number_format($price->quantity_stock) }}
                                    </span>
                                    @else
                                    <span class="font-bold text-lg text-green-600 dark:text-green-400">
                                        {{ number_format($price->quantity_stock) }}
                                    </span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="text-gray-400 mb-2">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                            No quantity stock
                        </p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mt-auto pt-4">

                <!-- edit -->
                <button 
                    @click="$dispatch('open-edit-modal', {id: {{ $product->id }}, name: '{{ $product->name }}'})"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 group">

                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>

                    Edit

                </button>
                
                <!-- delete -->
                <button 
                    @click="$dispatch('open-delete-modal', {id: {{ $product->id }}, name: '{{ $product->name }}'})"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 group">
                    
                    <!-- icon -->
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>

                    Delete
                    
                </button>
            </div>

        </div>

        <!-- Subtle gradient overlay for extra depth -->
        <div class="absolute inset-0 bg-gradient-to-t from-transparent to-transparent group-hover:from-indigo-50/10 group-hover:to-transparent transition-all duration-300 pointer-events-none rounded-2xl"></div>
    </div>
</div>