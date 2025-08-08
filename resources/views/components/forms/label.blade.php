@props(['name', 'label'])


<label for="{{ $name }}" {{ $attributes->merge(['class' => 'text-xl']) }}>
    {{ $label }}
</label>