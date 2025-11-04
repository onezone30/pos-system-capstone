@props([
    'id' => null,
    'name' => null, 
    'label' => null,
    'rows' => 4,
    'placeholder' => false,
    'class' => 'w-full px-6 py-4 bg-white text-black rounded-md',
    ])


@php

    $model = $attributes->get('wire:model') ?? $name;

    $error = $errors->first($model);

@endphp

<x-forms.field :label="$label" :name="$model">

        <textarea 
            rows="{{ $rows }}"
            {{ $attributes->merge(['class' => 'block w-full p-2.5 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400']) }}
        ></textarea>
    

</x-forms.field>
