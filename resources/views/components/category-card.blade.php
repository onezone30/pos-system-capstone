@props(['category'])

<x-button
    data-modal-target="view-category-modal-{{ $category->id }}"
    data-modal-toggle="view-category-modal-{{ $category->id }}"
    color="green"
    class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
    <div class="flex flex-col justify-between p-4 leading-normal">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ $category->name }}
        </h5>

        @if($category->description)
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
            {{ $category->description }}
        </p>
        @else
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
            No Description
        </p>
        @endif
    </div>
</x-button>
