@props([
    'id' => null,
    'name' => null, 
    'label' => null,
    'rows' => 4,
    'placeholder' => false,
])


@php

    $model = $attributes->get('wire:model') ?? $name;
    $error = $errors->first($model);

    $baseClass = '
        block w-full p-2.5 text-base rounded-lg 
        bg-gray-50 dark:bg-gray-800 
        border border-gray-300 dark:border-gray-600
        text-gray-900 dark:text-white 
        placeholder-gray-400 dark:placeholder-gray-400
        focus:ring-blue-500 focus:border-blue-500
        transition duration-150 ease-in-out
    ';
    
    $errorClass = $error 
        ? ' border-red-500 dark:border-red-400 focus:border-red-500 focus:ring-red-500' 
        : '';
    
    $finalClass = trim($baseClass . $errorClass);

@endphp

<x-forms.field :label="$label" :name="$model" :error="$error">

    <textarea 
        rows="{{ $rows }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $attributes->merge(['class' => $finalClass]) }}
    ></textarea>
    
</x-forms.field>