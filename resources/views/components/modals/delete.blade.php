<div 
    x-data="{ open: false, id: null, name: '' }"
    x-on:open-delete-modal.window="
        id = $event.detail.id;
        name = $event.detail.name;
        open = true
    "
    x-on:close-delete-modal.window="open = false"
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

            <button 
                type="submit"
                @click="$wire.delete(id)"
                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none 
                        focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm 
                        px-5 py-2.5">
                Yes, delete it
            </button>

            <button 
                type="button"
                @click="open = false"
                class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border rounded-lg 
                        hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 
                        dark:hover:text-white dark:hover:bg-gray-700">
                Cancel
            </button>

        </div>
    </div>
</div>
