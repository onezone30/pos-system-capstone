<div
    x-data="{ 
        open: false, 
        id: '', 
        name: '', 
        role: '', 
        email: '', 
        profile_image: '', 
        email_verified_at: '', 
        created_at: '', 
        updated_at: '',
        
        // --- UPDATED DATA VARIABLES ---
        order_count: '15', 
        total_spent: '7,500.00', 
        last_order_date: '2025-12-10 14:30:00',
        total_customers_catered: '120', // <<< NEW VARIABLE ADDED
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        getRoleColor(r) {
            r = r.toLowerCase();
            if (r.includes('admin') || r.includes('administrator')) return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
            if (r.includes('manager') || r.includes('cashier')) return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'; // Adjusted for Cashier
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        }
    }"
    x-on:open-view-modal.window="
        id = $event.detail.id; 
        name = $event.detail.name; 
        role = $event.detail.role; 
        email = $event.detail.email; 
        profile_image = $event.detail.profile_image; 
        email_verified_at = $event.detail.email_verified_at; 
        created_at = $event.detail.created_at; 
        updated_at = $event.detail.updated_at; 
        
        // --- Placeholder Data assignment (if you update your dispatch event) ---
        order_count = $event.detail.order_count ?? this.order_count; 
        total_spent = $event.detail.total_spent ?? this.total_spent; 
        last_order_date = $event.detail.last_order_date ?? this.last_order_date; 
        total_customers_catered = $event.detail.total_customers_catered ?? this.total_customers_catered; // <<< NEW DATA ASSIGNMENT
        
        open = true;
    "
    x-on:close-view-modal.window="open = false"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    x-cloak
    wire:ignore.self
    x-on:click.self="open = false"
    class="fixed inset-0 z-50 flex justify-center items-center p-4 bg-gray-900/50 dark:bg-gray-900/70"
>
    <div x-on:click.stop class="relative w-full max-w-xl max-h-full">
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-y-auto max-h-[90vh]">
            
            <div class="sticky top-0 z-10 flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/95 backdrop-blur-sm">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    User Profile: <span class="text-indigo-600 dark:text-indigo-400" x-text="name"></span>
                </h3>
                <button 
                    type="button" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition"
                    x-on:click="open = false"
                >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="p-6 space-y-6">
                
                <div class="flex flex-col items-center space-y-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="relative">
                        <img 
                            :src="profile_image ? ('/storage/' + profile_image) : '{{ asset('storage/images/profiles/default.jpg') }}'" 
                            :alt="name + ' Profile'" 
                            class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 dark:border-indigo-900 shadow-xl"
                        >
                        
                        <div class="mt-3 text-center">
                             <span 
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold capitalize"
                                :class="getRoleColor(role)"
                                x-text="role || 'N/A'"
                            ></span>
                        </div>
                    </div>
                </div>

                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">General Information</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border p-4 rounded-lg bg-gray-50 dark:bg-gray-700/30">
                    
                    <div class="flex flex-col">
                        <span class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Full Name</span>
                        <span class="text-base font-semibold text-gray-800 dark:text-gray-200" x-text="name"></span>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Email Address</span>
                        <a :href="'mailto:' + email" class="text-base text-blue-600 dark:text-blue-400 hover:underline truncate" x-text="email"></a>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Email Verified</span>
                        <template x-if="email_verified_at">
                            <span class="inline-flex items-center text-sm font-medium text-green-600 dark:text-green-400">Verified</span>
                        </template>
                        <template x-if="!email_verified_at">
                            <span class="inline-flex items-center text-sm font-medium text-yellow-600 dark:text-yellow-400">Pending</span>
                        </template>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-xs uppercase text-gray-500 dark:text-gray-400 font-medium">Member Since</span>
                        <span class="text-sm text-gray-800 dark:text-gray-200" x-text="formatDate(created_at)"></span>
                    </div>

                </div>

                <h4 class="text-lg font-semibold text-gray-900 dark:text-white pt-4">Business Metrics</h4>
                <div class="grid grid-cols-2 gap-4 text-center">
                    
                    <div class="p-3 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 shadow-sm border border-indigo-100 dark:border-indigo-900">
                        <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400" x-text="order_count"></span>
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400 mt-1">Total Orders</p>
                    </div>

                    <div class="p-3 rounded-lg bg-green-50 dark:bg-green-900/20 shadow-sm border border-green-100 dark:border-green-900">
                        <span class="text-2xl font-extrabold text-green-600 dark:text-green-400">$<span x-text="total_spent"></span></span>
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400 mt-1">Total Spent</p>
                    </div>

                    <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 shadow-sm border border-blue-100 dark:border-blue-900">
                        <span class="text-2xl font-extrabold text-blue-600 dark:text-blue-400" x-text="total_customers_catered"></span>
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400 mt-1">Customers Catered</p>
                    </div>
                    
                    <div class="p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 shadow-sm border border-yellow-100 dark:border-yellow-900">
                        <span class="text-base font-semibold text-yellow-600 dark:text-yellow-400" x-text="formatDate(last_order_date)"></span>
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400 mt-1">Last Order Date</p>
                    </div>

                </div>

            </div>

            <div class="flex items-center justify-between p-4 md:p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    User ID: <span x-text="id"></span> | Last updated: <span x-text="formatDate(updated_at)"></span>
                </span>
                <button 
                    x-on:click="open = false" 
                    type="button" 
                    class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition"
                >
                    Close
                </button>
            </div>
            
        </div>
    </div>
</div>