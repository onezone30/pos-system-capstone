<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-on:open-create-modal.window="open = true"
    x-on:close-create-modal.window="open = false"
    x-transition
    class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 dark:bg-gray-900/70 backdrop-blur-sm"
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg p-6 md:p-8 max-h-[90vh] overflow-y-auto"
            @click.away="$dispatch('close-create-modal')"
        >
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New User</h2>
                <button 
                    @click="$dispatch('close-create-modal')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                >
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="create">
                <div class="space-y-6">

                    <section class="space-y-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="ph ph-user-plus text-xl mr-2 text-indigo-500"></i> Account Details
                        </h3>
                        
                        <x-forms.input 
                            label="User Name"
                            wire:model="name"
                            placeholder="Enter user name"
                        />

                        <x-forms.input 
                            wire:model="email"
                            type="email"
                            placeholder="Enter email"
                            label="Email"
                        />
                        
                        <x-forms.select label="Select Role" wire:model="role">
                            <option value="">-- Select Role --</option>
                            @foreach (App\Models\User::ROLES as $role)
                                <option value="{{ $role }}">
                                    {{ ucfirst($role) }}
                                </option> 
                            @endforeach
                        </x-forms.select>
                    </section>

                    <section class="space-y-4 pt-2 border-t border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="ph ph-image text-xl mr-2 text-green-500"></i> Profile Picture
                        </h3>

                        <x-forms.file 
                            label="Upload Profile Picture"
                            wire:model="profile_image"
                            accept="image/*"
                        />

                        <div class="flex items-end gap-6 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border dark:border-gray-700">
                            @if ($profile_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <img 
                                    src="{{ $profile_image->temporaryUrl() }}" 
                                    alt="New Image Preview" 
                                    class="h-24 w-24 rounded-full object-cover shadow-md border-4 border-indigo-200 dark:border-indigo-600"
                                >
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Preview Ready</p>
                                    <x-button 
                                        size="sm" 
                                        color="red" 
                                        wire:click="$set('profile_image', null)"
                                    >
                                        <i class="ph ph-x text-lg mr-1"></i> Remove
                                    </x-button>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No profile image selected.</p>
                            @endif
                        </div>
                    </section>

                    <section class="space-y-4 pt-2 border-t border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="ph ph-lock-key text-xl mr-2 text-red-500"></i> Security
                        </h3>

                        <x-forms.password
                            wire:model="password"
                            name="password"
                        placeholder="Enter password"
                            label="Password" 
                        />
                        
                        <x-forms.password
                            wire:model="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm password"
                            label="Confirm Password" 
                        />
                    </section>

                    <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                        <x-button size="2xl" color="blue" wire:click="create" wire:target="create" class="w-full sm:w-auto">
                            <i class="ph ph-user-plus text-xl mr-2"></i> Create User
                        </x-button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>