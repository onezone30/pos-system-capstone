<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-10"
    x-on:open-create-modal.window="open = true"
    x-on:close-create-modal.window="open = false"
>
    <form wire:submit.prevent="create">

        <x-forms.input 
            label="Name"
            name="name"
            placeholder="Enter name"
            wire:model="name"/>

        <x-forms.input 
            label="Description"
            name="description"
            placeholder="Enter description"
            wire:model="description"/>

        <div class="flex justify-end mt-4">
            <x-button
                size="2xl"
                color="blue"
                wire:click="create"
                wire:target="create">
                Create Category
            </x-button>
        </div>

    </form>
</div>