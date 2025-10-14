@props([
    'name' => 'password',
    'label' => 'Password',
    'placeholder' => 'Enter your password',
    'class' => 'w-full px-6 py-4 bg-white text-black rounded-md',
])

@php

    $model = $attributes->get('wire:model') ?? $name;

    $error = $errors->first($model);

@endphp

<x-forms.field :label="$label" :name="$model">
    <div class="relative w-full">
        <input
            {{ $attributes }}
            type="password"
            name="{{ $name }}"
            class="{{ $class }} pr-12"
            placeholder="{{ $placeholder }}"
        />

        <!-- Toggle button -->
        <button type="button"
            onclick="togglePassword('{{ $name }}')"
            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-gray-900">
            <span id="show-icon-{{ $name }}">👁️</span>
            <span id="hide-icon-{{ $name }}" style="display:none;">🙈</span>
        </button>
    </div>
</x-forms.field>

@once
    <script>
        function togglePassword(name) {
            const input = document.getElementById(`password-input-${name}`);
            const showIcon = document.getElementById(`show-icon-${name}`);
            const hideIcon = document.getElementById(`hide-icon-${name}`);

            if (input.type === "password") {
                input.type = "text";
                showIcon.style.display = "none";
                hideIcon.style.display = "inline";
            } else {
                input.type = "password";
                showIcon.style.display = "inline";
                hideIcon.style.display = "none";
            }
        }
    </script>
@endonce
