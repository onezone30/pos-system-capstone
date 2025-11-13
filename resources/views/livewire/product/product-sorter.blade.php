<form wire:submit.prevent="sort">
    <div class="flex items-end gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-300">Category</label>
            <x-forms.select wire:model.live="category">
                <option value="">
                    All
                </option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-forms.select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Order By</label>
            <x-forms.select wire:model.live="field">
                <option value="name">Name</option>
                <option value="price">Price</option>
                <option value="created_at">Newest</option>
            </x-forms.select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Direction</label>
            <x-forms.select wire:model.live="order">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </x-forms.select>
        </div>
        <div>
            <x-button type="submit" class="py-5 px-5">
                Sort
            </x-button>
        </div>
    </div>
</form>
