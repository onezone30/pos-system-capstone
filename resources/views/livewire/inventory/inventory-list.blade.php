<div
    x-data="inventoryFilter()"
    @inventory-filter.window="updateFilter($event.detail)"
    @product-search.window="search = $event.detail"
    class="relative overflow-x-auto shadow-md sm:rounded-lg pb-4">
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
        
        <div class="p-3 sm:p-4 bg-green-50 dark:bg-green-900/50 rounded-xl shadow-md border border-green-100 dark:border-green-900">
            <p class="text-xs sm:text-sm font-medium text-green-700 dark:text-green-300 mb-1">Total Products</p>
            <p class="text-xl md:text-2xl font-extrabold text-green-800 dark:text-green-200 truncate">
                {{ $products->total() }}
            </p>
        </div>

        <div class="p-3 sm:p-4 bg-red-50 dark:bg-red-900/50 rounded-xl shadow-md border border-red-100 dark:border-red-900">
            <p class="text-xs sm:text-sm font-medium text-red-700 dark:text-red-300 mb-1">Low Stock Items</p>
            <p class="text-xl md:text-2xl font-extrabold text-red-800 dark:text-red-200 truncate">
                {{ $lowStockCount ?? 0 }}
            </p>
        </div>

        <div class="p-3 sm:p-4 bg-yellow-50 dark:bg-yellow-900/50 rounded-xl shadow-md border border-yellow-100 dark:border-yellow-900">
            <p class="text-xs sm:text-sm font-medium text-yellow-700 dark:text-yellow-300 mb-1">Total Categories</p>
            <p class="text-xl md:text-2xl font-extrabold text-yellow-800 dark:text-yellow-200 truncate">
                {{ \App\Models\Category::count() }}
            </p>
        </div>

        <div class="p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/50 rounded-xl shadow-md border border-blue-100 dark:border-blue-900">
            <p class="text-xs sm:text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Product Variants</p>
            <p class="text-xl md:text-2xl font-extrabold text-blue-800 dark:text-blue-200 truncate">
                {{ $products->sum(fn($p) => $p->prices->count()) }}
            </p>
        </div>
    </div>

    <!-- Inventory Table -->
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-center text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">Product Name</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Variant / Size</th>
                <th class="px-6 py-3">Stock</th>
                <th class="px-6 py-3">Reorder Level</th>
                <th class="px-6 py-3">Price</th>
                <th class="px-6 py-3">Last Updated</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($products as $product)
                @foreach ($product->prices as $price)
                    <tr 
                        x-show="matches(
                            $el.dataset.name,
                            $el.dataset.category,
                            $el.dataset.stock,
                            $el.dataset.level,
                        )"
                        x-cloak
                        data-name="{{ $product->name }}"
                        data-category="{{ $product->category->id }}"
                        data-stock="{{ $price->quantity_stock }}"
                        data-level="{{ $price->reorder_level }}"
                    >
                        <td class="px-6 py-4">{{ $product->name }}</td>
                        <td class="px-6 py-4">{{ $product->category->name }}</td>
                        <td class="px-6 py-4">{{ $price->size ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span 
                                class="px-2 py-1 rounded text-white text-xs font-semibold whitespace-nowrap
                                    @if($price->quantity_stock <= $price->reorder_level) 
                                        bg-red-600 dark:bg-red-500
                                    @elseif($price->quantity_stock <= ($price->reorder_level * 3)) 
                                        bg-yellow-500 dark:bg-yellow-400
                                    @else 
                                        bg-green-600 dark:bg-green-500
                                    @endif
                                "
                            >
                                {{ $price->quantity_stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $product->prices->pluck('reorder_level')->first() }}</td>
                        <td class="px-6 py-4">₱{{ number_format($price->price, 2) }}</td>
                        <td class="px-6 py-4">{{ $price->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <x-button @click="$dispatch('open-edit-modal', {price_id: {{ $price->id }}})">
                                    Edit
                                </x-button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            <!-- Modals -->
            <x-modals.edit>
                <livewire:inventory.edit-inventory-form />
            </x-modals.edit>
            <x-modals.delete />
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>

</div>

@push('js')

<script>
    const inventoryFilter = () => {
        return {
            search: '',
            category: '',
            showLowStockOnly: false,
            showMediumStockOnly: false,
            showHighStockOnly: false,

            updateFilter(payload) {
                this.category = payload.category ?? '';
                this.showLowStockOnly = payload.showLowStockOnly ?? false;
                this.showMediumStockOnly = payload.showMediumStockOnly ?? false;
                this.showHighStockOnly = payload.showHighStockOnly ?? false;
            },

            matches(name, categoryId, stock, level) {
                const nameStr = (name || '').toLowerCase();
                const stockNum = parseInt(stock || 0);
                const levelNum = parseInt(level || 0);
                const query = this.search.toLowerCase().trim();

                if (query && !nameStr.includes(query)) {
                    return false;
                }

                if (this.category && parseInt(categoryId) !== parseInt(this.category)) {
                    return false;
                }

                const wantsLow = this.showLowStockOnly;
                const wantsMedium = this.showMediumStockOnly;
                const wantsHigh = this.showHighStockOnly;

                const low = stockNum <= levelNum;
                const medium = stockNum > levelNum && stockNum <= levelNum * 3;
                const high = stockNum > levelNum * 3;

                if (!wantsLow && !wantsMedium && !wantsHigh) {
                    return true;
                }

                if (wantsLow && low) return true;

                if (wantsMedium && medium) return true;

                if (wantsHigh && high) return true;

                return false;
            }
        }
    }
</script>

@endpush