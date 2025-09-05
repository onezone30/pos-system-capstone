@props([
    'name' => 'password',
    'label' => 'Password',
    'placeholder' => 'Enter your password',
    'class' => 'w-full px-6 py-4 bg-white text-black rounded-md',
])

<x-forms.field :label="$label" :name="$name">
    <div x-data="{ show: false }" class="relative w-full">
        <input
            :type="show ? 'text' : 'password'"
            name="{{ $name }}"
            class="{{ $class }} pr-12" {{-- padding-right for button space --}}
            placeholder="{{ $placeholder }}"
        />

        <!-- Toggle button -->
        <button type="button"
            @click="show = !show"
            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-gray-900">
            <span x-show="!show">👁️</span>
            <span x-show="show">🙈</span>
        </button>
    </div>
</x-forms.field>
