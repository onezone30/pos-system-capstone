@props(['header', 'id'])

<!-- Main modal -->
<div
    x-data="{ open: false, id: '', name: '', role: '', email: '', profile_image: '', email_verified_at: '', created_at: '', updated_at: ''}"
    x-on:open-view-modal.window="
        id = $event.detail.id; 
        name = $event.detail.name; 
        role = $event.detail.role; 
        email = $event.detail.email; 
        profile_image = $event.detail.profile_image; 
        email_verified_at = $event.detail.email_verified_at; 
        created_at = $event.detail.created_at; 
        updated_at = $event.detail.updated_at; 
        open = true"
    x-on:close-view-modal.window="open = false"
    x-show="open"
    x-transition
    x-cloak
    wire:ignore.self
    x-on:click="open = false"
    class="overflow-y-auto overflow-x-hidden fixed py-6 top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
>
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="px-4 py-2 relative bg-white rounded-lg shadow-sm dark:bg-gray-700 ">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    User Information
                </h3>
                <button 
                    type="button" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    x-on:click="open = false"
                >
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal body -->
            <!-- Profile Image Section -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <template x-if="profile_image">
                        <img 
                            :src="'/storage/' + profile_image" 
                            :alt="name + `'s Profile`" 
                            class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600 shadow-lg"
                        >
                    </template>
                    <template x-if="!profile_image">
                        <div class="w-24 h-24 rounded-full bg-gray-300 dark:bg-gray-600 border-4 border-gray-200 dark:border-gray-600 shadow-lg flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </template>
                    
                    <!-- Role indicator badge -->
                    <template x-if="role === 'admin'">
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-purple-500 border-2 border-white dark:border-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </template>

                    <template x-if="role === 'cashier'">
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-blue-500 border-2 border-white dark:border-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </template>

                    <template x-if="role !== 'admin' && role !== 'cashier'">
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 border-2 border-white dark:border-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </template>
                </div>
            </div>

            <!-- User Details Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white w-1/3">
                                Full Name
                            </td>
                            <td class="px-4 py-3" x-text="name"></td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                Email Address
                            </td>
                            <td class="px-4 py-3">
                                <a :href="'mailto:' + email" class="text-blue-600 dark:text-blue-400 hover:underline" x-text="email"></a>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                Role
                            </td>
                            <td class="px-4 py-3">
                                <span x-show="role === 'admin'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                    Administrator
                                </span>
                                <span x-show="role === 'cashier'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    Cashier
                                </span>
                                <span x-show="role !== 'admin' && role !== 'cashier'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Customer
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                Email Verified
                            </td>
                            <td class="px-4 py-3">
                                <template x-if="email_verified_at">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Verified
                                    </span>
                                </template>
                                <template x-if="!email_verified_at">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Pending Verification
                                    </span>
                                </template>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                Member Since
                            </td>
                            <td class="px-4 py-3" x-text="created_at"></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                Last Updated
                            </td>
                            <td class="px-4 py-3" x-text="updated_at"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- User ID Section (Optional) -->
            <div class="mt-6 text-center">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    User ID: <span x-text="id"></span>
                </span>
            </div>
        </div>
    </div>
</div>
