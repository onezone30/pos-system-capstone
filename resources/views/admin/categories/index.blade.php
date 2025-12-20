<x-main>

        <x-page-title text="Categories" />

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="w-full sm:w-auto sm:flex-1 sm:max-w-md">
            <livewire:search />
        </div>

        <div class="flex-shrink-0">
            <x-button 
                x-data 
                x-on:click="$dispatch('open-create-modal')"
                class="w-full sm:w-auto"
            >
                Add Category
            </x-button>
        </div>

        <x-modals.create header="Create Category">
            <livewire:category.create-category-form />
        </x-modals.create>

    </div>
        
        <x-section>

            <livewire:category.category-list/>

        </x-section>

</x-main>

