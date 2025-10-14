<x-main>

        <h1 class="text-2xl font-bold">
            Categories
        </h1>

        <div class="flex justify-between items-center">

            <livewire:search />

            <x-button x-data x-on:click="$dispatch('open-create-modal')">
                Add Category
            </x-button>

            <x-modals.create header="Create Category">
                <livewire:category.create-category-form />
            </x-modals.create>

        </div>
        
        <x-section>

            <livewire:category.category-list/>

        </x-section>

</x-main>