<div class="flex space-x-2">
    <x-forms.select wire:model.live="selectedRange" class="w-40">
        <option value="today">Today</option>
        <option value="3">Last 3 days</option>
        <option value="7">Last 7 days</option>
        <option value="14">Last 14 days</option>
        <option value="30">Last 1 month</option>
        <option value="60">Last 2 months</option>
        <option value="all">All Time</option>
    </x-forms.select>
    <x-forms.select wire:model.live="order">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
    </x-forms.select>
    @if(auth()->user()->role !== 'cashier')
        <x-forms.select wire:model.live="user">
            <option value="">All Users</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </x-forms.select>
    @endif
</div>