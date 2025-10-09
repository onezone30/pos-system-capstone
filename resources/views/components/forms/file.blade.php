@props([
    'name' => null, 
    'label' => false,
    'type' => 'file',
    'placeholder' => null,
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
        class="{{ $class }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes }}/>

    <div wire:loading wire:target="{{ $model }}" class="my-5 text-sm text-white text-center w-full">
        Uploading image...
    </div>

    @php
    
        $file = data_get($this, $model)

    @endphp

    @if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)

        <img 
            class="mt-3 rounded-md max-h-48 mx-auto"
            alt="{{ $label }}"
            src="{{ $file->temporaryUrl() }}">
    
    @endif

    

</x-forms.field>
