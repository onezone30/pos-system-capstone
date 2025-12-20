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
    <div class="group relative flex flex-col h-full bg-white border border-gray-100 rounded-2xl shadow-xl hover:shadow-2xl dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        
        <div class="relative overflow-hidden shrink-0">
            <div class="aspect-square p-6 bg-gray-50 dark:bg-gray-700/50 flex justify-center items-center">
            @if ($product->product_image === null)
                <img 
                    class="w-full h-full object-contain rounded-xl group-hover:scale-105 transition-transform duration-300"
                    src="{{ asset('storage/images/products/default.png') }}"
                    alt="{{ $product->name }}" 
                />
            @else
                <img 
                    class="w-full h-full object-contain rounded-xl group-hover:scale-105 transition-transform duration-300"
                    src="{{ asset('storage/' . $product->product_image) }}"
                    alt="{{ $product->name }}" 
                />
            @endif
            </div>
            
            <div class="absolute top-4 right-4 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">
                {{ $product->prices ? $product->prices->count() : 0 }} Options
            </div>
            
            <div class="absolute top-4 left-4 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">
                {{ $product->category?->name ?? 'No Category' }}
            </div>
        </div>

        <div class="flex flex-col flex-1 p-6">
            
            <h5 class="mb-1 text-2xl font-extrabold text-center text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200 whitespace-nowrap overflow-hidden text-ellipsis">
                {{ $product->name }}
            </h5>

            <hr class="my-4 border-gray-100 dark:border-gray-700">

            <div class="flex flex-col flex-1 min-h-[120px] mb-4">
                <div class="flex items-center justify-between mb-3 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <span class="w-1/3 text-left">Size</span>
                    <span class="w-1/3 text-center">Stock</span>
                    <span class="w-1/3 text-right">Price</span>
                </div>
                
                @if($product->prices && $product->prices->count() > 0)
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach ($product->prices as $price)
                            @php
                                $q = $price->quantity_stock;
                                $reorder = $price->reorder_level;
                                $stockClass = '';
                                if ($q < $reorder) {
                                    $stockClass = 'text-red-600 dark:text-red-400';
                                } elseif ($q < $reorder * 3 && $q > 0) {
                                    $stockClass = 'text-yellow-600 dark:text-yellow-400';
                                } elseif ($q >= $reorder * 3) {
                                    $stockClass = 'text-green-600 dark:text-green-400';
                                } else {
                                    $stockClass = 'text-gray-500 dark:text-gray-400';
                                }
                                $isPriceConfigured = !empty($price->price) && $price->price != 0;
                            @endphp

                            <div class="grid grid-cols-3 items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 shadow-sm">
                                
                                <div class="font-medium text-gray-700 dark:text-gray-300 capitalize text-left">
                                    {{ $price->size }}
                                </div>
                                
                                <div class="font-bold text-base text-center {{ $stockClass }}">
                                    @if ($q > 0)
                                        {{ number_format($q) }}
                                        <div class="text-[10px] text-gray-500 dark:text-gray-400 italic mt-0.5">
                                            (RL: {{ number_format($reorder) }})
                                        </div>
                                    @else
                                        <span class="text-xs text-red-500 dark:text-red-400">Out of Stock</span>
                                    @endif
                                </div>
                                
                                <div class="font-black text-lg text-indigo-600 dark:text-indigo-400 text-right">
                                    @if ($isPriceConfigured)
                                        ₱{{ number_format($price->price, 2) }}
                                    @else
                                        <span class="text-xs text-red-500 dark:text-red-400">N/A</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                        <div class="text-gray-400 mb-2">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">No price/stock options configured.</p>
                    </div>
                @endif
            </div>

            <div class="flex gap-3 mt-auto pt-4 border-t border-gray-100 dark:border-gray-700/50">

                <button 
                    @click="$dispatch('open-edit-modal', {id: {{ $product->id }}, name: '{{ $product->name }}'})"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl group transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-indigo-500/50">
                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
                
                <button 
                    @click="$dispatch('open-delete-modal', {id: {{ $product->id }}, name: '{{ $product->name }}'})"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl group transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-red-500/50">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
        
        <div class="absolute inset-0 bg-gradient-to-t from-transparent to-transparent group-hover:from-indigo-50/10 group-hover:to-transparent transition-all duration-300 pointer-events-none rounded-2xl"></div>
    </div>
</div>