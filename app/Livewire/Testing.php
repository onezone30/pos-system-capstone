<?php

namespace App\Livewire;

use App\Models\Animal;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Testing extends Component
{
    use WithFileUploads;

    #[Rule('required|min:3|max:50')]
    public string $name = "";

    #[Rule('nullable|sometimes|image')]
    public $image;


    public function create()
    {
        // Validate all except the file first, or include file but store immediately
        Log::info('Image:', [$this->image]); // Should show TemporaryUploadedFile
        $this->validate([
            'name' => 'required|min:3|max:50',
            'image' => 'nullable|sometimes|image|max:2048', 
        ]);
        Log::info('After validate:', [$this->image]); // Might be null or gone


        $data = ['name' => $this->name];

        if ($this->image) {
            // Store the file immediately after validation
            $data['image'] = $this->image->store('animals', 'public');
        }

        Animal::create($data);

        $this->reset('name', 'image');
    }

    public function render()
    {
        $animals = Animal::get();

        return view('livewire.testing', [
            'animals' => $animals
        ]);
    }
}
