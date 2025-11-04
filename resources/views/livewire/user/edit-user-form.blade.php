<div 
    x-on:open-edit-modal.window="open = true"
    x-on:close-edit-modal.window="open = false"
    x-transition
    x-data="{ open: false }"
    x-show="open"
    x-cloak
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

            @if ($profile_image && !$profile_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <div class="flex items-center justify-center gap-4">
                    <img 
                        src="{{ asset('storage/' . $profile_image) }}" 
                        alt="Product Image"
                        class="max-h-48 rounded-lg object-cover border"
                    >

                    <x-button 
                        size="sm" 
                        color="red" 
                        wire:click="removeProductImage"
                        wire:loading.attr="disabled"
                    >
                        Remove
                    </x-button>
                </div>
            @endif

            @if ($profile_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <div class="flex items-center gap-4">
                    <img 
                        src="{{ $profile_image->temporaryUrl() }}" 
                        alt="Preview" 
                        class="max-h-48 rounded-lg object-cover border"
                    >
                    <x-button 
                        size="sm" 
                        color="red" 
                        wire:click="$set('profile_image', null)"
                    >
                        Cancel Upload
                    </x-button>
                </div>
            @endif

            <x-forms.password
                wire:model="password"
                name="password"
                placeholder="Enter password"
                label="Password" />

            <x-forms.password
                wire:model="password_confirmation"
                name="password_confirmation"
                placeholder="Enter confirm password"
                label="Confirm Password" />

            <!-- Create Button -->
            <div class="flex justify-end mt-4">
                <x-button size="2xl" color="blue" wire:click="update" wire:target="update">
                    Update User
                </x-button>
            </div>

        </div>
    </form>


</div>