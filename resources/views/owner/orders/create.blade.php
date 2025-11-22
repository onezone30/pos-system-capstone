<x-main>
    <x-page-title text="Create Order" />

    <div 
        x-data="{ search: '' }"
        class="flex justify-between items-center mb-4"
    >
        <!-- Search Input -->
        <x-forms.input 
            x-model="search"
            @input="$dispatch('product-search', search)"
            placeholder="Search products..." 
        />            

        <!-- Cart Button -->
        <x-button
            x-data="{ count: 0 }"
            x-on:cart-count-update.window="count = $event.detail.count" 
            x-on:click="$dispatch('open-create-modal')"
        >
            Check Cart
            <span
                x-text="count" 
                class="inline-flex items-center justify-center w-4 h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
            </span>
        </x-button>

        <livewire:cart-component />
    </div>

    <div 
        x-data="{
            order: '',
            category: '',
            stock: '',

            updateFilters(){
                $dispatch('product-filter', {
                    order: this.order,
                    category: this.category,
                    stock: this.stock,
                });
            }
        }
        "
    >
        <!-- Sorter -->
        <div class="flex gap-2 mb-4">
            <x-forms.select 
                x-model="category" 
                @change="updateFilters()"
            >
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                <option value="{{ $category->name }}">
                    {{ $category->name }}
                </option>
                @endforeach
            </x-forms.select>
            <x-forms.select 
                x-model="stock" 
                @change="updateFilters()"
            >
                <option value="">All Stocks</option>
                <option value="low">Low Stock</option>
                <option value="medium">Medium Stock</option>
                <option value="high">High Stock</option>
            </x-forms.select>
            <x-forms.select x-model="order" @change="updateFilters()">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </x-forms.select>
        </div>

        <x-section>

            <livewire:product-order-list :products="$products" />
            
        </x-section>
    </div>
</x-main>

