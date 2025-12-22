<x-main>

    <main class="mt-14 max-w-4xl mx-auto px-6 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <i class="ph ph-clock-counter-clockwise mr-3 text-indigo-500"></i> 
                    Activity Log
                </h1>
                <p class="text-gray-400 mt-1">Real-time track of sales, stock movements, and product adjustments.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="flex items-center text-xs text-gray-400">
                    <span class="w-3 h-3 bg-green-600 rounded-full mr-1"></span> Sales
                </span>
                <span class="flex items-center text-xs text-gray-400">
                    <span class="w-3 h-3 bg-blue-600 rounded-full mr-1"></span> Stock In
                </span>
                <span class="flex items-center text-xs text-gray-400">
                    <span class="w-3 h-3 bg-red-600 rounded-full mr-1"></span> Stock Out
                </span>
                <span class="flex items-center text-xs text-gray-400">
                    <span class="w-3 h-3 bg-purple-600 rounded-full mr-1"></span> Settings
                </span>
            </div>
        </div>

        <div class="relative">
            <div class="absolute left-4 md:left-8 top-0 bottom-0 w-0.5 bg-gray-700"></div>

            @forelse ($activities as $activity)
                @php
                    // Logic to determine appearance based on activity type
                    $isSale = $activity->log_type === 'sale';
                    $isSetting = $activity->log_type === 'setting';
                    
                    if ($isSale) {
                        $icon = 'ph-shopping-cart';
                        $colorClass = 'bg-green-600 ring-green-900/30';
                        $borderClass = 'border-green-500/20';
                    } elseif ($isSetting) {
                        $icon = 'ph-gear-six';
                        $colorClass = 'bg-purple-600 ring-purple-900/30';
                        $borderClass = 'border-purple-500/20';
                    } else {
                        $icon = $activity->type === 'in' ? 'ph-trend-up' : 'ph-trend-down';
                        $colorClass = $activity->type === 'in' ? 'bg-blue-600 ring-blue-900/30' : 'bg-red-600 ring-red-900/30';
                        $borderClass = $activity->type === 'in' ? 'border-blue-500/20' : 'border-red-500/20';
                    }
                @endphp

                <div class="relative pl-12 md:pl-20 mb-10 group">
                    <span class="absolute -left-2 md:left-2 top-2 w-12 text-[10px] font-bold text-gray-500 uppercase vertical-text hidden md:block">
                        {{ $activity->created_at->format('M d') }}
                    </span>

                    <div class="absolute left-1.5 md:left-5.5 top-0 w-6 h-6 md:w-8 md:h-8 rounded-full {{ $colorClass }} ring-4 flex items-center justify-center z-10 transition-transform group-hover:scale-110">
                        <i class="ph {{ $icon }} text-white text-sm md:text-base"></i>
                    </div>

                    <div class="bg-gray-800 border {{ $borderClass }} rounded-xl p-5 shadow-xl hover:bg-gray-750 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                            <span class="text-xs text-gray-500 font-mono">
                                {{ $activity->created_at->format('h:i A') }} ({{ $activity->created_at->diffForHumans() }})
                            </span>
                            <span class="text-xs text-gray-400 flex items-center">
                                <i class="ph ph-user-circle mr-1 text-base"></i>
                                {{ $activity->user->name ?? 'System' }}
                            </span>
                        </div>

                        @if($isSale)
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-white">Order Processed</h3>
                                    <p class="text-gray-400 text-sm mt-1">
                                        Received <span class="text-green-400 font-bold">₱{{ number_format($activity->total_amount, 2) }}</span> 
                                        from <span class="text-gray-200">{{ $activity->customer_name ?? 'Walk-in Customer' }}</span>
                                    </p>
                                </div>
                                <span class="px-2 py-1 bg-gray-900 rounded text-[10px] font-bold text-gray-400 uppercase border border-gray-700">
                                    {{ $activity->payment_method }}
                                </span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($activity->items as $item)
                                    <span class="px-2 py-1 bg-gray-900/50 text-gray-400 text-[11px] rounded border border-gray-700">
                                        {{ $item->quantity }}x {{ $item->product->name ?? '' }}
                                    </span>
                                @endforeach
                            </div>

                        @elseif($isSetting)
                            <h3 class="text-lg font-bold text-white">Configuration Change</h3>
                            <div class="mt-2 p-3 bg-gray-900/50 rounded-lg border border-purple-500/10">
                                <p class="text-sm text-gray-300 italic">
                                    "{{ $activity->note }}"
                                </p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Product: <span class="text-purple-400">{{ $activity->product->name ?? '' }}</span></p>

                        @else
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-white">
                                    Stock {{ $activity->type === 'in' ? 'Replenished' : 'Removed' }}
                                </h3>
                                <span class="text-xl font-black {{ $activity->type === 'in' ? 'text-blue-500' : 'text-red-500' }}">
                                    {{ $activity->type === 'in' ? '+' : '-' }}{{ $activity->quantity }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-400 mt-1">
                                <span class="text-gray-200 font-medium">{{ $activity->product->name ?? '' }}</span> 
                                stock level was adjusted.
                            </p>
                            @if($activity->note)
                                <p class="text-xs text-gray-500 mt-2 italic">Note: {{ $activity->note }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-gray-800 rounded-xl border border-dashed border-gray-700">
                    <i class="ph ph-tray text-5xl text-gray-600"></i>
                    <p class="text-gray-500 mt-4 font-medium">No activity recorded yet.</p>
                </div>
            @endforelse

        </div>
    </main>
</x-main>
