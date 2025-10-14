@props(['card_user'])

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
    <div class="h-full max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg flex flex-col">
        
        <!-- Card Body (stretchable part) -->
        <div class="flex-1 px-8 py-4">
            <div class="text-center my-4">
                <!-- profile -->
                @if($card_user->profile_image == null)
                    <img 
                        class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                        src="{{ asset('storage/images/profiles/default-user.jpg') }}" 
                        alt="{{ $card_user->name }}">
                @else
                    <img 
                        class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                        src="{{ asset('storage/' . $card_user->profile_image) }}" 
                        alt="{{ $card_user->name }}">
                @endif
                
                <!-- name and role -->
                <div class="py-2">
                    <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1 w-full break-words">
                        {{ $card_user->name ?? 'User Name' }}
                    </h3>
                    <div class="inline-flex text-gray-700 dark:text-gray-300 items-center">
                        <i class="ph ph-user-square"></i>
                        {{ $card_user->role ?? 'User Role' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Button section (always pinned at bottom) -->
        <div class="flex justify-center gap-2 px-4 py-3 mt-auto dark:border-gray-700">
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
                    id: '{{ $card_user->id }}',
                    name: '{{ $card_user->name }}',
                    email: '{{ $card_user->email }}',
                    role: '{{ $card_user->role }}',
                    profile_image: '{{ $card_user->profile_image ?? '' }}',
                    email_verified_at: '{{ $card_user->email_verified_at }}',
                    created_at: '{{ $card_user->created_at }}',
                    updated_at: '{{ $card_user->updated_at }}'
                })"
                color="green">
                View
            </x-button>
        </div>

    </div>
</div>
