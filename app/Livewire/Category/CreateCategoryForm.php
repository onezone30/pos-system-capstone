<?php

namespace App\Livewire\Category;

use App\Models\Category;
use App\Services\CategoryServices;
use Livewire\Component;

class CreateCategoryForm extends Component
{
    public string $name;
    public string $description;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255']
        ];
    }

    public function create(CategoryServices $service)
    {
        $this->validate();

        $categoryData = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        $service->create($categoryData);

        $this->dispatch('close-create-modal');
        $this->dispatch('createCategory');
        $this->dispatch('toast.success', message: "{$this->name} category has been created");
        $this->reset();
    }

    public function render()
    {
        return view('livewire.category.create-category-form');
    }
}
