@props(['name'=> null, 'label'])

@php

$defaults = [
    'id' => $name,
    'name' => $name,
    'class' => 'rounded-xl bg-white/50 border border-white/10 px-5 py-4 w-full text-black'
];

$model = $attributes->get('wire:model') ?? $name;

$error = $errors->first($model);

@endphp

<x-forms.field :label="$label" :name="$model">
    <select {{ $attributes($defaults) }}>
        {{ $slot }}
    </select>
</x-forms.field>