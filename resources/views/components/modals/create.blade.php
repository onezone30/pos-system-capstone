@props(['header'])

<!-- Modal backdrop -->
<div
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-transition
    x-on:open-create-modal.window="open = true"
    x-on:close-create-modal.window="open = false"
    x-on:createProduct.window="open = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Backdrop overlay -->
    <div class="fixed inset-0 bg-black/50" @click="$dispatch('close-create-modal')"></div>
    
    <!-- Modal container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl">
            <!-- Modal content -->
            <div class="px-4 py-2 relative bg-white rounded-lg shadow-lg dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-3xl font-semibold text-gray-900 dark:text-white">
                        {{ $header }}
                    </h3>
                    <button 
                        type="button" 
                        @click="$dispatch('close-create-modal')"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                
                <!-- Modal body -->
                {{ $slot }}

            </div>
        </div>
    </div>
</div>