@props([
    'name', 
    'label',
    'type' => 'text',
    'placeholder' => false,
    'class' => 'w-full px-6 py-4 bg-white text-black rounded-md'
    ])


<x-forms.field>

    <x-forms.label :name="$name" :label="$label" />

        <input 
            label="{{ $label }}"
            type="{{ $type }}" 
            name="{{ $name }}" 
            class="{{ $class }}"
            placeholder="{{ $placeholder }}" />

    

</x-forms.field>
