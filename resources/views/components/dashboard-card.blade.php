@props(['title', 'value'])



<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-100 dark:border-gray-700 hover:shadow-md transition" title="{{ $tooltip ?? '' }}">
    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-400">{{ $title }}</h3>
    <p class="text-2xl font-bold mt-2">{{ $value }}</p>
</div>