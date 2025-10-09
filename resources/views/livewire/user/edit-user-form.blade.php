<div 
    x-on:open-edit-modal.window="open = true"
    x-on:close-edit-modal.window="open = false"
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-10"
>

    <form wire:submit.prevent="update">
        <div class="space-y-6">

            <x-forms.input 
                label="Name"
                wire:model="name"
                placeholder="Enter user name"/>

            <x-forms.select label="Select Role" wire:model="role">
                @foreach (App\Models\User::ROLES as $role)
                    <option value="{{ $role }}">
                        {{ ucfirst($role) }}
                    </option>   
                @endforeach
            </x-forms.select>

            <x-forms.input 
                wire:model="email"
                type="email"
                placeholder="Enter email"
                label="Email"/>

            <x-forms.file 
                label="Profile Picture"
                wire:model="profile_image"/>

            <x-forms.password 
                wire:model="password"
                name="password"
                placeholder="Enter password"
                label="Password"/>
            
            <x-forms.password 
                wire:model="password_confirmation"
                name="password_confirmation"
                placeholder="Enter confirm password"
                label="Confirm Password"/>


            <!-- Create Button -->
            <div class="flex justify-end mt-4">
                <x-button size="2xl" color="blue" wire:click="update" wire:target="update">
                    Update User
                </x-button>
            </div>

        </div>
    </form>


</div>