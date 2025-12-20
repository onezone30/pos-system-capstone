@props([
    'name' => 'password',
    'label' => 'Password',
    'placeholder' => 'Enter your password',
    'class' => 'p-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
])

@php
    $model = $attributes->get('wire:model') ?? $name;
    $uniqueId = $name . '-' . Str::random(4); 
    $error = $errors->first($model);
@endphp

<x-forms.field :label="$label" :name="$model">
    <div class="relative w-full">
        <input
            {{ $attributes }}
            id="password-input-{{ $uniqueId }}"
            type="password"
            name="{{ $name }}"
            class="{{ $class }} pr-12"
            placeholder="{{ $placeholder }}"
        />

        <button type="button"
            onclick="togglePassword('{{ $uniqueId }}')"
            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <span id="show-icon-{{ $uniqueId }}"><i class="ph ph-eye text-xl"></i></span>
            <span id="hide-icon-{{ $uniqueId }}" style="display:none;"><i class="ph ph-eye-slash text-xl"></i></span>
        </button>
    </div>
</x-forms.field>

@once
    <script>
        function togglePassword(id) {
            const input = document.getElementById(`password-input-${id}`);
            const showIcon = document.getElementById(`show-icon-${id}`);
            const hideIcon = document.getElementById(`hide-icon-${id}`);

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