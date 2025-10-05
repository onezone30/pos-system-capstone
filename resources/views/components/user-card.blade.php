@props(['card_user'])

<!-- Card start -->
<div
wire:key="user-card-{{ $card_user->id }}"
x-data="{ show: false }"
x-init="setTimeout(() => show = true, 200)"
x-show="show"
x-transition:enter="transition ease-out duration-500"
x-transition:enter-start="opacity-0 -translate-y-10"
x-transition:leave="transition ease-in duration-300"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 -translate-y-10"                     
>
    <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg flex flex-col">
        <div class="px-8 py-4 flex-1">
            <div class="text-center my-4">

                <!-- profile -->
                <img 
                    class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                    src="{{ asset('storage/' . $card_user->profile_image) }}" 
                    alt="">


                <div class="py-2">
                    
                    <!-- name -->
                    <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1 w-full break-words">
                        {{ $card_user->name ?? 'User Name'}}
                    </h3>
                    <div class="inline-flex text-gray-700 dark:text-gray-300 items-center">

                        <!-- role -->
                        <i class="ph ph-user-square"></i>
                        {{ $card_user->role ?? 'User Role'}}
                    </div>
                </div>
            </div>
            <div class="flex justify-center gap-2 px-2">
                <x-button 
                    @click="$dispatch('open-delete-modal', {id: {{ $card_user->id }}, name: '{{ $card_user->name }}'})"
                    color="red">
                    Delete
                </x-button>
                <x-button
                    @click="$dispatch('open-edit-modal', {id: {{ $card_user->id }}, name: '{{ $card_user->name }}'})">
                    Edit
                </x-button>
                <x-button 
                    @click="$dispatch('open-view-modal', { 
                        id: '{{ $user->id }}',
                        name: '{{ $user->name }}',
                        email: '{{ $user->email }}',
                        role: '{{ $user->role }}',
                        profile_image: '{{ $user->profile_image ? asset('storage/' . $user->profile_image) : '' }}',
                        email_verified_at: '{{ $user->email_verified_at }}',
                        created_at: '{{ $user->created_at }}',
                        updated_at: '{{ $user->updated_at }}'
                    })"
                    color="green">
                    View
                </x-button>
            </div>
        </div>
    </div>
</div>
<!-- Card end -->

