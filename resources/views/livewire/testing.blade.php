<div>
    <form wire:submit.prevent="create" class="bg-black/50 flex flex-col justify-center" enctype="multipart/form-data">

        <label for="name">
            Name
        </label>
        <input id="name" type="text" wire:model="name">
        @error('name')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror

        <label for="image">
            Image
        </label>
        <input 
            wire:model="image" 
            accept="image/jpg, image/jpeg, image/png" 
            id="image" 
            type="file" >
        @error('image')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror

        <button type="submit">
            Submit
        </button>

    </form>

    <div>

        @foreach ($animals as $animal)
            <div wire:key="{{ $animal->id }}">
                <h1>
                    {{ $animal->name }}
                </h1>
                @if($animal->image)
                    <img src="{{ asset('storage/' . $animal->image) }}" alt="">
                @else
                    <p>No Image</p>
                @endif
            </div>
        @endforeach

    </div>


</div>