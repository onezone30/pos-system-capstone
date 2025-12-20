<x-main>
    <x-page-title text="Create Order" />

    <div 
        x-data="{ search: '' }"
        class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3 sm:gap-4"
    >
        <div class="flex-1 w-full sm:w-auto">
             <x-forms.input 
                x-model.debounce.500ms="search"
                @input="$dispatch('product-search', search)"
                placeholder="Search products by name..." 
            />
        </div>

        <x-button
            x-data="{ count: 0 }"
            x-on:cart-count-update.window="count = $event.detail.count" 
            x-on:click="$dispatch('open-create-modal')"
            class="w-full sm:w-auto justify-center sm:justify-start py-3"
        >
            <i class="ph ph-shopping-cart text-xl me-2"></i>
            Check Cart
            <span
                x-text="count" 
                :class="count > 0 ? 'bg-indigo-600' : 'bg-gray-400'"
                class="inline-flex items-center justify-center w-5 h-5 ms-2 text-xs font-semibold text-white rounded-full transition-colors duration-200"
            >
            </span>
        </x-button>

        <livewire:cart-component />
    </div>

    <div 
        x-data="{
            order: 'asc', 
            category: '',
            stock: '',

            updateFilters(){
                $dispatch('product-filter', {
                    order: this.order,
                    category: this.category,
                    stock: this.stock,
                });
            }
        }"
        x-init="updateFilters"
    >
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-row gap-3 mb-6">
             <x-forms.select 
                x-model="category" 
                @change="updateFilters()"
                class="w-full lg:w-64"
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
                class="w-full lg:w-48"
            >
                <option value="">All Stocks</option>
                <option value="low">Low Stock</option>
                <option value="medium">Medium Stock</option>
                <option value="high">High Stock</option>
            </x-forms.select>
            
            <x-forms.select 
                x-model="order" 
                @change="updateFilters()"
                class="w-full sm:col-span-2 lg:w-48 lg:ml-auto"
            >
                <option value="asc">Name (A-Z)</option>
                <option value="desc">Name (Z-A)</option>
            </x-forms.select>
        </div>

        <x-section>
            <livewire:product-order-list :products="$products" />
        </x-section>
    </div>
</x-main>