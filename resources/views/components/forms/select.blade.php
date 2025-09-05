@props(['name', 'label'])

@php

$defaults = [
    'id' => $name,
    'name' => $name,
    'class' => 'rounded-xl bg-white/50 border border-white/10 px-5 py-4 w-full text-black'
]

@endphp

<x-forms.field :label="$label" :name="$name">
    <select {{ $attributes($defaults) }}>
        {{ $slot }}
    </select>
</x-forms.field>