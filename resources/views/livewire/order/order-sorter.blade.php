<div class="
    grid grid-cols-2 
    md:flex 
    gap-3 sm:gap-4 
    items-center 
    w-full
">
    <div class="col-span-1">
        <x-forms.select wire:model.live="selectedRange" class="w-full md:w-40">
            <option value="today">Today</option>
            <option value="3">Last 3 days</option>
            <option value="7">Last 7 days</option>
            <option value="14">Last 14 days</option>
            <option value="30">Last 1 month</option>
            <option value="60">Last 2 months</option>
            <option value="all">All Time</option>
        </x-forms.select>
    </div>

    <div class="col-span-1">
        <x-forms.select wire:model.live="order" class="w-full">
            <option value="asc">Sort: Ascending</option>
            <option value="desc">Sort: Descending</option>
        </x-forms.select>
    </div>

    @if(auth()->user()->role !== 'cashier')
        <div class="
            col-span-2 md:col-span-1 
            md:flex-1
        ">
            <x-forms.select wire:model.live="user" class="w-full">
                <option value="">All Users</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </x-forms.select>
        </div>
    @endif
</div>