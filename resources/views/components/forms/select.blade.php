@props(['name'=> null, 'label' => null])

@php

$defaults = [
    'id' => $name,
    'name' => $name,
    'class' => 'px-3 py-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
];

$model = $attributes->get('wire:model') ?? $name;

$error = $errors->first($model);

@endphp

<x-forms.field :label="$label" :name="$model">
    <select {{ $attributes->merge($defaults) }}>
        {{ $slot }}
    </select>
</x-forms.field>