<?php

namespace App\Livewire\Category;

use App\Services\CategoryServices;
use Livewire\Component;

class CreateCategoryForm extends Component
{
    public string $name;
    public string $description;

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string']
        ];
    }

    public function create(CategoryServices $service)
    {
        $this->validate();

        $categoryData = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        $service->createCategory($categoryData);

        $this->dispatch('close-create-modal');
        $this->dispatch('createCategory');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.category.create-category-form');
    }
}
