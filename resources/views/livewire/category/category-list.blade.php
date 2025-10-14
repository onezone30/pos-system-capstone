<div
    id="product-list"
    class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 items-stretch">
    
    @foreach ($categories as $category)
        <x-category-card :category="$category" />
    @endforeach

    <x-modals.edit action="edit">
        <livewire:category.edit-category-form />
    </x-modals.edit>

    <x-modals.delete action="delete" />
</div>