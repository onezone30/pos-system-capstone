@props(['class' => 'px-6 py-4 bg-emerald-500 hover:bg-emerald-600 rounded-xl font-bold uppercase cursor-pointer'])


<button {{ $attributes }} class="{{ $class }}">
    {{ $slot }}
</button>