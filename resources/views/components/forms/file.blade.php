@props([
    'name' => null, 
    'label' => false,
    'type' => 'file',
    'placeholder' => null,
    'class' => 'px-3 py-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
    ])


@php

    $model = $attributes->get('wire:model') ?? $name;

    $error = $errors->first($model);

@endphp

<x-forms.field :label="$label" :name="$model">

    <input 
        accept="image/*"
        label="{{ $label }}"
        type="{{ $type }}" 
        name="{{ $name }}" 
        class="{{ $class }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes }}/>

    <div wire:loading wire:target="{{ $model }}" class="my-5 text-sm text-white text-center w-full">
        Uploading image...
    </div>

</x-forms.field>
