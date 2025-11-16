<x-main>

        <x-page-title text="Products" />

        <div
            x-data="{ 
                search: '', 
            }" 
            class="mt-4 flex justify-between items-center">

            <x-forms.input
                x-model="search"
                @input="$dispatch('product-search', search)"
                placeholder="Search products..." /> <!-- search bar -->
        
            <x-button x-data x-on:click="$dispatch('open-create-modal')">
                Add Product
            </x-button>
            
            <!-- add modal -->
            <x-modals.create header="Create Product">
                <livewire:product.create-product-form />  
            </x-modals.create>

        </div>

        <div
            x-data="{ 
                category: '',
                order: '',
                stock: '',
  
                updateFilters() {
                    $dispatch('product-filter', {
                        category: this.category,
                        order: this.order,
                        stock: this.stock
                    });
                },
            }"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 "
        >

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
                x-model="order" 
                @change="updateFilters()"
            >
                <option value="">Default</option>
                <option value="asc">Ascending (A → Z)</option>
                <option value="desc">Descending (Z → A)</option>
            </x-forms.select>

            <x-forms.select 
                x-model="stock" 
                @change="updateFilters()"
            >
                <option value="">All Stocks</option>
                <option value="low">Low Stock (0–10)</option>
                <option value="medium">Medium Stock (11–50)</option>
                <option value="high">High Stock (51+)</option>
            </x-forms.select>
            
        </div>
        
        <x-section>

            <livewire:product.product-list />

        </x-section>
</x-main>


