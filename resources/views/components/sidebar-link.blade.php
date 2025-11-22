@props(['icon'])

<a {{ $attributes }} class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
    @isset($icon)
        <span class="me-3">{{ $icon }}</span>
    @endisset
    {{ $slot }}
</a>
