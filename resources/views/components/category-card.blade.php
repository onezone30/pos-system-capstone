@props(['category'])

<div
    class="flex flex-col justify-between h-full bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 p-4">
    
    {{-- Card Content --}}
    <div>
        <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
            {{ $category->name }}
        </h5>

        <p class="mb-3 text-sm text-gray-700 dark:text-gray-400">
            {{ $category->description ?: 'No Description' }}
        </p>
    </div>

    {{-- Action Buttons --}}
    <div class="mt-4 flex justify-end gap-2">
        <button 
            @click="$dispatch('open-edit-modal', { id: {{ $category->id }} })"
            class="px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 transition">
            Edit
        </button>

        <button 
            @click="$dispatch('open-delete-modal', { id: {{ $category->id }} })"
            class="px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-300 transition">
            Delete
        </button>
    </div>

</div>
