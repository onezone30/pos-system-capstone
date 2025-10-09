<div 
    x-data="{ open: false, id: null, name: '' }"
    x-on:open-delete-modal.window="
        id = $event.detail.id;
        name = $event.detail.name;
        open = true
    "
    x-on:success-delete.window="
        open = false;
        $dispatch('close-delete-modal');
    "
    x-show="open"
    x-transition
    x-cloak
    wire:ignore.self
    x-on:click="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md w-full max-w-md p-6">
        <!-- Close Button -->
        <button 
            type="button"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-900 dark:hover:text-white"
            @click="open = false"
        >
            ✕
        </button>

        <div class="text-center">

            <!-- Icon -->
            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" 
                 fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" 
                      stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>

            <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400">
                Are you sure you want to delete <b x-text="name"></b>?
            </h3>
            <p class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                This action <b>cannot</b> be undone.
            </p>


            <x-button 
                color="red" 
                wire:click="delete(id)"
                wire:target="delete">
                Yes, delete it
            </x-button>

            <x-button 
                color="red" 
                @click="open = false" 
                wire:target="delete">
                Cancel
            </x-button>

        </div>
    </div>
</div>
