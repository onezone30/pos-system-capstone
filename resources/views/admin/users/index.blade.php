<x-main>

    <x-page-title text="User" />

    <div class="flex justify-between items-center">

        <livewire:search placeholder="name, role, email"/>

        <!-- Create User Button -->
        <x-button x-data x-on:click="$dispatch('open-create-modal')">
            Create User
        </x-button>
        <!-- Create User Form -->
        <x-modals.create header="Create User">
            <livewire:user.create-user-form />
        </x-modals.create>

    </div>

    <x-section>

        <livewire:user.user-list />

    </x-section>



</x-main>