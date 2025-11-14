<div 
    x-data="productFilter()"
    @product-search.window="search = $event.detail"
>
    <div
        id="product-list" 
        class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        @forelse ($products as $product)
        <div x-show="matches($el.dataset.name, $el.dataset.category)"
            x-cloak
            data-name="{{ $product->name }}"
            data-category="{{ $product->category->name ?? '' }}"
        >
            <x-product-card :product="$product" />
        </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-4">
                No products found.
            </div>
        @endforelse

        <!-- rendering modals -->
        <!-- Edit Modal -->
        <x-modals.edit>
            <livewire:product.edit-product-form />
        </x-modals.edit>
        <!-- Delete Modal -->
        <x-modals.delete />

    </div>
</div>

@push('js')
    <script>
        const productFilter = () => {
            return {
                search: '',

                matches(name, category) {
                    const s = this.search.toLowerCase().trim()
                    if(!s) return true;

                    return (
                        name.includes(s) ||
                        category.includes(s)
                    )
                }
            }
        }
    </script>
@endpush