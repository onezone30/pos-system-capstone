<x-main>

    <x-page-title text="User" />

    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
        
        <div class="flex flex-col flex-1 gap-3 sm:flex-row sm:items-center">
            
            <div x-data="{ search: '' }" class="w-full sm:max-w-xs">
                <x-forms.input
                    x-model="search"
                    @input="$dispatch('user-search', search)"
                    placeholder="Search users..." 
                />
            </div>

            <div 
                class="w-full sm:w-48"
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
                    class="w-full"
                >
                    <option value="">-- All Roles --</option>
                    @foreach (App\Models\User::ROLES as $role)
                        <option value="{{ $role }}">
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </x-forms.select>
            </div>
        </div>

        <div class="flex-shrink-0">
            <x-button x-data x-on:click="$dispatch('open-create-modal')" class="w-full sm:w-auto">
                <i class="ph ph-plus-circle mr-2"></i> Create User
            </x-button>

            <x-modals.create header="Create User">
                <livewire:user.create-user-form />
            </x-modals.create>
        </div>
        
    </div>
    <x-section>

        <livewire:user.user-list />

    </x-section>



</x-main>