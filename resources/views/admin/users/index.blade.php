<x-main>

    <x-page-title text="User" />

    <div
        x-data="{ 
            search: ''
        }" 
        class="flex justify-between items-center"
    >

        <x-forms.input
            x-model="search"
            @input="$dispatch('user-search', search)"
            placeholder="Search users..." /> <!-- search bar -->

        <!-- Create User Button -->
        <x-button x-data x-on:click="$dispatch('open-create-modal')">
            Create User
        </x-button>
        <!-- Create User Form -->
        <x-modals.create header="Create User">
            <livewire:user.create-user-form />
        </x-modals.create>

    </div>

    <div
        class="w-1/4" 
        x-data="{
            role: '',

            updateFilters() {
                $dispatch('user-filter', {
                    role: this.role
                })
            }
        }"
    >

        <x-forms.select 
            @change="updateFilters()"    
            x-model="role"
        >
            <option value="">-- Select Role --</option>
            @foreach (App\Models\User::ROLES as $role)
            <option value="{{ $role }}">
                {{ ucfirst($role) }}
            </option>
            @endforeach
        </x-forms.select>

    </div>

    <x-section>

        <livewire:user.user-list />

    </x-section>



</x-main>