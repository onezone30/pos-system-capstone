<div
    x-data="productFilter()"
    @product-filter.window="updateFilter($event.detail)"
    @product-search.window="search = $event.detail"
>
    <div
        id="product-list" 
        class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @foreach ($products as $product)
        <div
            x-show="matches(
                $el.dataset.name,
                $el.dataset.category,
                $el.dataset.stock,
            )"
            x-cloak
            data-name="{{ $product->name }}"
            data-category="{{ $product->category->name ?? '' }}"
            data-stock="{{ $product->prices->first()->quantity_stock ?? 0 }}"
        >
            <livewire:product-card-order 
                :product="$product" 
                :key="$product->id" />
        </div>
        @endforeach


    </div>
</div>

@push('js')
    <script>
        const productFilter = () => {
            return {
                search: '',
                category: '',
                stock: '',
                order: '', 
                sortBy: 'name',  
                _sortTimer: null,

                updateFilter(payload) {
                    this.category = payload.category ?? '';
                    this.order = payload.order ?? '';
                    this.stock = payload.stock ?? '';

                    this._debouncedSort();
                },

                _debouncedSort() {
                    if (this._sortTimer) clearTimeout(this._sortTimer);
                    this._sortTimer = setTimeout(() => {
                        this.sortProducts();
                        this._sortTimer = null;
                    }, 40);
                },

                sortProducts() {
                    if (!this.order) return; 

                    const list = document.querySelector('#product-list');
                    if (!list) return;

                    // collect items that represent products (include hidden ones)
                    const items = Array.from(list.querySelectorAll('[data-name]'));

                    items.sort((a, b) => {
                        // choose key on which to sort
                        if (this.sortBy === 'name') {
                            const aVal = (a.dataset.name || '').toString().toLowerCase();
                            const bVal = (b.dataset.name || '').toString().toLowerCase();
                            return this.order === 'asc'
                                ? aVal.localeCompare(bVal)
                                : bVal.localeCompare(aVal);
                        }

                        // future: sort by numeric field (price / stock)
                        if (this.sortBy === 'stock') {
                            const aVal = parseInt(a.dataset.stock || '0', 10);
                            const bVal = parseInt(b.dataset.stock || '0', 10);
                            return this.order === 'asc' ? aVal - bVal : bVal - aVal;
                        }

                        if (this.sortBy === 'price') {
                            const aVal = parseFloat(a.dataset.price || '0');
                            const bVal = parseFloat(b.dataset.price || '0');
                            return this.order === 'asc' ? aVal - bVal : bVal - aVal;
                        }

                        return 0;
                    });

                    items.forEach(node => list.appendChild(node));
                },

                matches(name, category, stock) {
                    name = (name || '').toString().toLowerCase();
                    category = (category || '').toString().toLowerCase();
                    stock = parseInt(stock || '0', 10);

                    const s = this.search.toLowerCase().trim();

                    if (s && !(name.includes(s) || category.includes(s))) return false;

                    if (this.category && category !== this.category.toLowerCase()) return false;


                    if (this.stock === 'low' && stock > 10) return false;
                    if (this.stock === 'medium' && (stock < 11 || stock > 50)) return false;
                    if (this.stock === 'high' && stock < 51) return false;

                    return true;
                }
            }
        }
    </script>
@endpush
