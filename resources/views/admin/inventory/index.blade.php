<x-main>
    <!-- Page Title -->
    <x-page-title text="Inventory" />

    <div 
        x-data="{
            search: '',
            category: '',
            showLowStockOnly: false,
            showMediumStockOnly: false,
            showHighStockOnly: false,

            updateFilters() {
                $dispatch('inventory-filter', {
                    showLowStockOnly: this.showLowStockOnly,
                    showMediumStockOnly: this.showMediumStockOnly,
                    showHighStockOnly: this.showHighStockOnly,
                    category: this.category,
                });
            }
        }"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4"
    >
        <x-forms.input  
            @input="$dispatch('product-search', search)"
            placeholder="Search products..." 
            x-model="search" />

        <x-forms.select 
            @change="updateFilters()"
            x-model="category">
            <option value="">All Categories</option>
            @foreach (\App\Models\Category::all() as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </x-forms.select>

        <label class="mt-2 flex items-center gap-2">
            <input 
            type="checkbox" 
            class="checkbox"
            @change="updateFilters()" 
            x-model="showLowStockOnly" />
            Show Low Stock Only
        </label>

        <label class="mt-2 flex items-center gap-2">
            <input 
            type="checkbox" 
            class="checkbox"
            @change="updateFilters()" 
            x-model="showMediumStockOnly" />
            Show Medium Stock Only
        </label>

        <label class="mt-2 flex items-center gap-2">
            <input 
            type="checkbox" 
            class="checkbox"
            @change="updateFilters()" 
            x-model="showHighStockOnly" />
            Show High Stock Only
        </label>

    </div>

    <x-section>

        <livewire:inventory.inventory-list />

    </x-section>

</x-main>
