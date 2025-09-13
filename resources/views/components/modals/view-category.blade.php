@props(['header' => 'Category details', 'id', 'category'])

<!-- Main modal -->
<div id="view-category-modal-{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed py-6 top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="px-4 py-2 relative bg-white rounded-lg shadow-sm dark:bg-gray-700">

            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $header }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-toggle="view-category-modal-{{ $id }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white w-1/3">
                                Category Name
                            </td>
                            <td class="px-4 py-3">
                                {{ $category->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                Description
                            </td>
                            <td class="px-4 py-3">
                                @if($category->description)
                                    {{ $category->description }}
                                @else
                                    <span class="text-gray-400 italic">No description provided</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Category ID Section -->
            <div class="mt-6 text-center">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    Category ID: #{{ $category->id }}
                </span>
            </div>

            <!-- Modal footer -->
            <div
                class="flex items-center justify-end gap-3 mt-6 p-4 border-t border-gray-200 rounded-b dark:border-gray-600">
                <x-button 
                    data-modal-target="delete-modal-{{ $category->id }}"
                    data-modal-toggle="delete-modal-{{ $category->id }}"
                    color="red">
                    Delete
                </x-button>
                <x-button
                    data-modal-target="edit-modal-{{ $category->id }}"
                    data-modal-toggle="edit-modal-{{ $category->id }}">
                    Edit
                </x-button>
            </div>

        </div>
    </div>
</div>
