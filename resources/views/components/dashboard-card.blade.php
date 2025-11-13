@props(['title', 'value'])

@php
    $isPositive = str_contains($value, '↑');
    $color = $isPositive ? 'text-green-600' : 'text-red-600';
@endphp

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-400">{{ $title }}</h3>
    <p class="text-2xl font-bold mt-2 {{ $color }}">{{ $value }}</p>
</div>