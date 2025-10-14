@props([
    'name' => null, 
    'label' => false,
    'type' => 'text',
    'placeholder' => false,
    'class' => 'w-full px-6 py-4 bg-white text-black rounded-md',
    ])


@php

    $model = $attributes->get('wire:model') ?? $name;

    $error = $errors->first($model);

@endphp

<x-forms.field :label="$label" :name="$model">

        <input 
            label="{{ $label }}"
            type="{{ $type }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => $class]) }}/>
    

</x-forms.field>
