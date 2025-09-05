@props(['user'])

<!-- Card start -->
<div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg flex flex-col">
    <div class="px-8 py-4 flex-1">
        <div class="text-center my-4">

            <!-- profile -->
            <img 
                class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                src="{{ asset('storage/' . $user->profile_image) }}" 
                alt="">


            <div class="py-2">
                
                <!-- name -->
                <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1 w-full break-words">
                    {{ $user->name ?? 'User Name'}}
                </h3>
                <div class="inline-flex text-gray-700 dark:text-gray-300 items-center">

                    <!-- role -->
                    <i class="ph ph-user-square"></i>
                    {{ $user->role ?? 'User Role'}}
                </div>
            </div>
        </div>
        <div class="flex justify-center gap-2 px-2">
            <x-button 
                data-modal-target="delete-modal-{{ $user->id }}"
                data-modal-toggle="delete-modal-{{ $user->id }}"
                color="red">
                Delete
            </x-button>
            <x-button
                data-modal-target="edit-modal-{{ $user->id }}"
                data-modal-toggle="edit-modal-{{ $user->id }}">
                Edit
            </x-button>
            <x-button 
                data-modal-target="view-modal-{{ $user->id }}"
                data-modal-toggle="view-modal-{{ $user->id }}"
                color="green">
                View
            </x-button>
        </div>
    </div>
</div>
<!-- Card end -->

