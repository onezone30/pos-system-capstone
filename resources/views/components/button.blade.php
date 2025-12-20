@props([
    'color' => 'blue',
    'size' => 'base',
    'loading' => null
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
        'class' => "inline-flex items-center font-medium text-center text-white rounded-lg focus:ring-4 focus:outline-none transition-colors duration-200 $classes",
    ]) }}
    @if($loading)
        :disabled="{{ $loading }}"
        :class="{ 'opacity-50 cursor-not-allowed': {{ $loading }} }"
    @endif
    
    @if ($hasWire)
        wire:loading.class="opacity-50 cursor-not-allowed"
        wire:loading.attr="disabled"
    @endif
>
    @if($loading)
        <template x-if="{{ $loading }}">
            <svg class="inline w-4 h-4 me-3 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
        </template>
    @endif

    @if ($hasWire)
        <span wire:loading wire:target="{{ $attributes->get('wire:target') ?? $attributes->get('wire:click') }}">
             <svg class="inline w-4 h-4 animate-spin mr-2" ...>...</svg>
        </span>
    @endif

    <span>{{ $slot }}</span>
</button>
