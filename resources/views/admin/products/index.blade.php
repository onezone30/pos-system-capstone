<x-main>

        <x-page-title text="Products" />

        <div class="space-y-4">
            <div
                x-data="{ search: '' }" 
                class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="w-full sm:max-w-md">
                    <x-forms.input
                        x-model="search"
                        @input="$dispatch('product-search', search)"
                        placeholder="Search products..."
                    />
                </div>

                <x-button 
                    x-data 
                    x-on:click="$dispatch('open-create-modal')"
                    class="w-full sm:w-auto"
                >
                    Add Product
                </x-button>
                
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
                class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3"
            >
                <x-forms.select 
                    x-model="category" 
                    @change="updateFilters()"
                    class="w-full"
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
                    class="w-full"
                >
                    <option value="">Sort: Default</option>
                    <option value="asc">Ascending (A → Z)</option>
                    <option value="desc">Descending (Z → A)</option>
                </x-forms.select>

                <x-forms.select 
                    x-model="stock" 
                    @change="updateFilters()"
                    class="w-full sm:col-span-2 lg:col-span-1"
                >
                    <option value="">All Stocks</option>
                    <option value="low">Low Stock (0–10)</option>
                    <option value="medium">Medium Stock (11–50)</option>
                    <option value="high">High Stock (51+)</option>
                </x-forms.select>
            </div>
        </div>
        
        <x-section>

            <livewire:product.product-list />

        </x-section>
</x-main>


