@props([
    'class' => 'px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md uppercase tracking-wider transition duration-150',
    'type' => 'submit'
])


<button {{ $attributes->except('class') }} class="{{ $class }}" type="{{ $type }}">
    {{ $slot }}
</button>