<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-10"
    x-on:open-create-modal.window="open = true"
    x-on:close-create-modal.window="open = false"
>

    <form wire:submit.prevent="create">
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

            <x-forms.input 
                wire:model="profile_image"
                type="file" 
                label="Profile Image" />

            <x-forms.input 
                wire:model="password" 
                label="Password" 
                placeholder="Enter your password" 
                type="password" />

            <x-forms.input 
                wire:model="password_confirmation" 
                label="Confirm Password" 
                placeholder="Enter your confirm password" 
                type="password" />


            <!-- Create Button -->
            <div class="flex justify-end mt-4">
                <x-forms.button>
                    Create User
                </x-forms.button>
            </div>

        </div>
    </form>


</div>