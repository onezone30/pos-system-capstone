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
    x-on:open-edit-modal.window="open = true"
    x-on:close-edit-modal.window="open = false"
>

    <form wire:submit.prevent="update">

        <div class="space-y-6">

            <!-- Names -->
            <x-forms.input 
                wire:model="name"
                label="Name"
                placeholder="Enter name of product"/>

            <x-forms.textarea 
                wire:model="description"
                label="Description" 
                placeholder="Enter description"/>

            <!-- Edit Button -->
            <div class="flex justify-end mt-4">
                 <x-button size="2xl" color="blue" wire:click="update" wire:target="update">
                    Update Product
                </x-button>
            </div>
        </div>
    </form>
</div>