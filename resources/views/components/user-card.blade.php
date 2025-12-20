@props(['card_user'])

<div
    wire:key="user-card-{{ $card_user->id }}"
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:leave="transition ease-in duration-200"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    x-cloak
    class="h-full w-full"
>
    
    @php
        // --- 1. Role Styling Logic ---
        $role = strtolower($card_user->role ?? 'user');
        $roleColorClass = 'text-gray-500 dark:text-gray-400';

        if (str_contains($role, 'admin') || str_contains($role, 'administrator')) {
            $roleColorClass = 'text-red-600 dark:text-red-400';
        } elseif (str_contains($role, 'owner')) {
            $roleColorClass = 'text-blue-600 dark:text-blue-400';
        } elseif (str_contains($role, 'cashier')) {
            $roleColorClass = 'text-yellow-600 dark:text-yellow-400';
        } elseif (str_contains($role, 'customer')) {
            $roleColorClass = 'text-indigo-600 dark:text-indigo-400';
        }

        $imagePath = $card_user->profile_image 
            ? asset('storage/' . $card_user->profile_image) 
            : asset('storage/images/profiles/default.jpg');


        // --- 2. CALCULATE INDIVIDUAL METRICS FOR THIS USER ---
        // Requires 'orders' relationship to be eager loaded in the Controller
        
        // Count total orders 
        $userOrderCount = $card_user->orders->count();

        // Sum the total amount spent/catered
        $userTotalSpent = $card_user->orders->sum('total_amount');
        $userTotalSpentFormatted = number_format($userTotalSpent, 2, '.', ',');

        // Get the last order date (using null-safe operator for clean access)
        $userLastOrderDate = $card_user->orders->sortByDesc('created_at')->first()?->created_at->toDateTimeString() ?? '';

        // Count distinct customers catered by this user
        $userCateredCount = $card_user->orders->unique('customer_id')->count();
        
    @endphp

    <div class="h-full max-w-sm mx-auto bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col border border-gray-100 dark:border-gray-700/50">
        
        <div class="h-16 bg-indigo-500 dark:bg-indigo-900/50"></div>

        <div class="flex-1 px-6 py-4 -mt-16">
            <div class="text-center">
                
                <img 
                    class="h-28 w-28 rounded-full border-4 border-white dark:border-gray-800 mx-auto shadow-md object-cover"
                    src="{{ $imagePath }}" 
                    alt="{{ $card_user->name }}"
                >
                
                <div class="py-4">
                    <h3 class="font-extrabold text-2xl text-gray-900 dark:text-white mb-1 w-full break-words leading-snug">
                        {{ $card_user->name ?? 'User Name' }}
                    </h3>
                    <div class="inline-flex text-sm font-semibold uppercase items-center tracking-wider mt-1 {{ $roleColorClass }}">
                        <i class="ph ph-user-square mr-1"></i> 
                        {{ $card_user->role ?? 'User Role' }}
                    </div>
                </div>

                <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    {{ $card_user->email }}
                </div>
            </div>
        </div>

        <div class="flex justify-center gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
            
            <x-button 
                @click="$dispatch('open-delete-modal', {id: {{ $card_user->id }}, name: '{{ $card_user->name }}'})"
                color="red"
                size="sm"
            >
                Delete
            </x-button>

            <x-button
                @click="$dispatch('open-edit-modal', {id: {{ $card_user->id }}, name: '{{ $card_user->name }}'})"
                color="indigo"
                size="sm"
            >
                Edit
            </x-button>

            <x-button 
                @click="$dispatch('open-view-modal', { 
                    id: '{{ $card_user->id }}',
                    name: '{{ $card_user->name }}',
                    email: '{{ $card_user->email }}',
                    role: '{{ $card_user->role }}',
                    profile_image: '{{ $card_user->profile_image ?? '' }}',
                    email_verified_at: '{{ $card_user->email_verified_at }}',
                    created_at: '{{ $card_user->created_at }}',
                    updated_at: '{{ $card_user->updated_at }}',
                    
                    // --- NEW METRICS DISPATCHED HERE ---
                    order_count: '{{ $userOrderCount }}',
                    total_spent: '{{ $userTotalSpentFormatted }}',
                    total_customers_catered: '{{ $userCateredCount }}',
                    last_order_date: '{{ $userLastOrderDate }}'
                })"
                color="green"
                size="sm"
            >
                View
            </x-button>
        </div>

    </div>
</div>