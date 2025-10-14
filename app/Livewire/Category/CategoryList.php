<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryList extends Component
{
    protected $listeners = [
        'createCategory' => '$refresh',
        'deleteCategory' => '$refresh',
    ];

    public function delete(int $id)
    {
        Category::findOrFail($id)->delete();

        $this->dispatch('deleteCategory');
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.category.category-list', [
            'categories' => $categories
        ]);
    }
}
