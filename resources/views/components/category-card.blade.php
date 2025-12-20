@props(['category'])

<div
    class="flex flex-col justify-between h-full bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 
           hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-300 p-5 space-y-4"
>
    
    <div>
        <div class="flex items-start justify-between mb-3">
            <h5 class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">
                {{ $category->name }}
            </h5>
            
            @if(isset($category->products_count))
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300 whitespace-nowrap">
                {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
            </span>
            @endif
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
            {{ $category->description ?: 'No description provided for this category.' }}
        </p>
    </div>

    <div class="pt-3 border-t border-gray-100 dark:border-gray-700/50 flex justify-end gap-3">
        
        <button 
            @click="$dispatch('open-edit-modal', { id: {{ $category->id }} })"
            class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md 
                   hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 transition duration-150"
        >
            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-5L20 4m-3 7L13 13m-4 5h.01M16 7l2-2"></path></svg>
            Edit
        </button>

        <button 
            @click="$dispatch('open-delete-modal', { id: {{ $category->id }} })"
            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg shadow-md 
                   hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition duration-150"
        >
            <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Delete
        </button>
    </div>

</div>