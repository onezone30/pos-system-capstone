@props([
    'name', 
    'label' => false,
    'type' => 'text',
    'placeholder' => false,
    'class' => 'w-full px-6 py-4 bg-white text-black rounded-md',
    'value' => ''
    ])


<x-forms.field :label="$label" :name="$name">

        <input 
            label="{{ $label }}"
            type="{{ $type }}" 
            name="{{ $name }}" 
            class="{{ $class }}"
            placeholder="{{ $placeholder }}"
            value="{{ $value }}" />
    

</x-forms.field>
