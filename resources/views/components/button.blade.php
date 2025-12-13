@props([
    'color' => 'blue',
    'loading' => false,
    'size' => 'base'
])

@php
    $hasWire = $attributes->get('wire:click') || $attributes->get('wire:target');

    $colorClasses = [
        'blue' => 'bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
        'green' => 'bg-green-700 hover:bg-green-800 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800',
        'red' => 'bg-red-700 hover:bg-red-800 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800',
        'yellow' => 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800',
        'purple' => 'bg-purple-700 hover:bg-purple-800 focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800',
        'gray' => 'bg-gray-700 hover:bg-gray-800 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800',
        'indigo' => 'bg-indigo-700 hover:bg-indigo-800 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800',
    ];

    $sizes = [
        'xs' => 'px-3 py-2 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'base' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-base',
        'xl' => 'px-6 py-3.5 text-base',
    ];

    $classes = ($sizes[$size] ?? $sizes['base']) . ' ' . ($colorClasses[$color] ?? $colorClasses['blue']);
@endphp

<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => "inline-flex items-center font-medium text-center text-white rounded-lg focus:ring-4 focus:outline-none transition-colors duration-200 cursor-pointer $classes"
    ]) }}
    @if ($hasWire)
        wire:loading.class="opacity-50 cursor-not-allowed"
        wire:loading.attr="disabled"
        wire:target="{{ $attributes->get('wire:target') ?? $attributes->get('wire:click') }}"
    @endif
>
    @if ($hasWire)
        <span wire:loading.remove wire:target="{{ $attributes->get('wire:target') ?? $attributes->get('wire:click') }}">
            {{ $slot }}
        </span>

        <span wire:loading wire:target="{{ $attributes->get('wire:target') ?? $attributes->get('wire:click') }}">
            <svg class="inline w-4 h-4 animate-spin" viewBox="0 0 100 101" fill="none">
                <path ... />
                <path ... />
            </svg>
            Loading...
        </span>
    @else
        {{ $slot }}
    @endif
</button>
