<div>

    <form role="search">
        <x-forms.input 
            wire:model.live.debounce.500ms="search"
            :placeholder="$placeholder" />
    </form>

</div>