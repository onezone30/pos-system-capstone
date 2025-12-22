<?php

namespace App\Livewire\Category;

use App\Models\Category;
use App\Services\CategoryServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class EditCategoryForm extends Component
{
    public Category $category;
    public $name = '';
    public $description = '';

    public function rules()
    {
        return [
            'name' => ['string', 'required', 'max:255', Rule::unique('categories', 'name')->ignore($this->category->id)],
            'description' => ['string', 'nullable', 'max:255'],
        ];
    }  

    #[On('open-edit-modal')]
    public function load(int $id)
    {
        $this->category = Category::findOrFail($id);

        $this->name = $this->category->name;
        $this->description = $this->category->description;
    }

    public function update(CategoryServices $services)
    {
        $this->validate();

        $categoryData = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        if(!$services->update($this->category, $categoryData)) {
            $this->dispatch('toast.error', message: 'Error updating category');
            return;
        }

        $this->dispatch('editCategory');
        $this->dispatch('toast.success', message: "{$this->name} category has been updated");
        $this->dispatch('close-edit-modal');
    }

    public function render()
    {
        return view('livewire.category.edit-category-form');
    }
}
