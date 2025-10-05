<x-main>

    <div class="flex justify-between items-center">


        <h1 class="text-2xl font-bold">
            Users
        </h1>

        <!-- Dynamic Search Bar -->
        <x-forms.form method="GET" action="{{ route('admin.users.search') }}">

            <div class="flex gap-5">
                <x-forms.input name="search" type="search" placeholder="Search user" />

                <x-button color="blue">
                    Search
                </x-button>

            </div>

        </x-forms.form>
        
        <!-- Create User Button -->
        <x-button x-data x-on:click="$dispatch('open-create-modal')">
            Create User
        </x-button>
        <!-- Create User Form -->
        <x-modals.create header="Create User">
            @livewire('user.create-user-form')
        </x-modals.create>

    </div>

    <x-section>

        <livewire:user.user-list />

    </x-section>

    <!-- rendering modals -->
    <!-- Edit Modal -->
    <x-modals.edit action="edit">
        <livewire:user.edit-user-form />
    </x-modals.edit>
    <!-- View Modal -->
    <x-modals.view-user />

</x-main>